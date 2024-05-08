<?php

namespace App\Controller;

use App\Card\Desk;

use ReflectionClass;
use ReflectionMethod;
use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class MyJsonController extends AbstractController
{
    public RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    #[Route('/api', name: "api")]
    public function apiIndex(KernelInterface $kernel): Response
    {
        $routes = $this->router->getRouteCollection()->all();
        $jsonFile = $kernel->getProjectDir() . '/public/json/routerannotation.json';
        $jsonContent =  file_get_contents($jsonFile);
        $annotations = json_decode($jsonContent, true);
        $data = [];
        $response = new Response();

        foreach ($routes as $routeName => $route) {

            if (substr($routeName, 0, 1) !== '_') {
                // Populate data array
                $data[$routeName] = [
                    'name' => $routeName,
                    'path' => $route->getPath(),
                    'controller' => $route->getDefault('_controller'),
                    'annotation' => $annotations[$routeName],
                ];
            }
        }
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    #[Route('/api/quote', name: "api.quote")]
    public function geQuote(KernelInterface $kernel): Response
    {
        $jsonFile = $kernel->getProjectDir() . '/public/json/quote.json';
        $jsonContent =  file_get_contents($jsonFile);

        $response = new Response();

        $data = json_decode($jsonContent, true);

        $quoteIndex = random_int(0, count($data) - 1);
        $randomQuote = $data[$quoteIndex];
        $response->setContent(json_encode($randomQuote));

        //return $this->render('api_quote.html.twig', $randomQuote);
        return $response;
    }

    #[Route('/api/desk', name: "api_desk", methods:['GET'])]
    public function apiDesk(
        SessionInterface $session
    ): Response {
        $data = [];

        try {
            $data = $session->get('desk');
            if ($data == null || count($data) < 52) {
                $newDesk = new Desk();
                $data = $newDesk->getDesk();
            }
        } catch (Exception $e) {
            $desk = new Desk();
            $data = $desk->getDesk();
        }

        $session->set('desk', $data);

        $response = new Response();
        $response->setContent(json_encode($data));

        return $response;
    }


    #[Route('/api/desk/shuffle', name: "api_desk_shuffle", methods:['POST'])]
    public function apiShuffleDesk( ): Response
    {
        $desk = new Desk();
        $desk->getDesk();
        $desk->shuffleDesk();

        $data = $desk->getDesk();
        $response = new Response();

        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/api/desk/draw', name: "api_desk_draw", methods:['POST'])]
    public function apiDrawDesk(
        SessionInterface $session
    ): Response {
        $data = [];

        try {
            $data = $session->get('desk');
            if ($data == null || count($data) < 52) {
                $desk = new Desk();
                $data = $desk->getDesk();
            }
        } catch (Exception $e) {
            $desk = new Desk();
            $data = $desk->getDesk();
        }

        $element = array_rand($data);
        $card = $data[$element];
        unset($data[$element]);
        $number = count($data);
        $result = [
            'card' => $card,
            'number' => $number,
        ];

        $session->set('drawed', $result);
        $session->set('desk', $data);
        $response = new Response();
        $response->setContent(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('api/deck/draw/{num_card}', name: "api_desk_draw_flera", methods:['POST'])]
    public function apiDrawFleraDesk(
        SessionInterface $session,
        Request $request
    ): Response {
        $response = new Response();
        $numCard = $request->request->get('num_card');
        $data = [];
        $hand = [];

        try {
            $data = $session->get('desk');
            if ($data == null || count($data) < 52) {
                $desk = new Desk();
                $data = $desk->getDesk();
            }
        } catch (Exception $e) {
            $desk = new Desk();
            $data = $desk->getDesk();
        }

        for($i = 0; $i < $numCard; $i++) {
            $element = array_rand($data);
            $card = $data[$element];
            $hand[] = $card;
            unset($data[$element]);
        }
        $number = count($data);
        $result = [
            'drown' => $hand,
            'number' => $number,
        ];

        $session->set('desk', $data);
        $session->set('hand', $hand);
        $response->setContent(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('api/deck/deal/{play}/{cards}', name: "api_desk_deal", methods:['POST'])]
    public function apiDealCard(
        SessionInterface $session,
        int $play,
        int $cards
    ): Response {
        $data = [];
        $players = [];
        $response = new Response();
        try {
            $data = $session->get('desk');
            if ($data == null || count($data) < 52) {
                $desk = new Desk();
                $data = $desk->getDesk();
            }
        } catch (Exception $e) {
            $desk = new Desk();
            $data = $desk->getDesk();
        }

        shuffle($data);
        for($k = 0; $k < $play; $k++) {
            $hand = [];
            for($i = 0; $i < $cards; $i++) {
                $element = array_rand($data);
                $card = $data[$element];
                $hand[] = $card;
                unset($data[$element]);
            }
            $players[(string)($k + 1)] = $hand;
        }

        $number = count($data);
        $result = [
            'players' => $players,
            'number cards left' => $number,
        ];

        $session->set('players', $players);
        $session->set('desk', $data);

        $response = new Response();
        $response->setContent(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/session', name: 'get_session')] // get all frome session
    public function apiGetSession(
        SessionInterface $session
    ): JsonResponse
    {
        $data = [];

        foreach ($session->all() as $key => $value) {
            $data[$key] = $value;
        }

        // Return the session data as JSON
        return $this->json($data);
    }

    #[Route('api/game', name: 'json_cardplay21')] // get all frome session
    public function apiGetGameStatus(
        SessionInterface $session
    ): Response
    {
        $data = [];

        foreach ($session->all() as $key => $value) {
            if($key == "desk" | $key == "bank" | $key == "player") {
                $data[$key] = $value;
            }
        }

        if(count($data) == 0) {
            $data["status"] = "cardplay was not initiated";
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
