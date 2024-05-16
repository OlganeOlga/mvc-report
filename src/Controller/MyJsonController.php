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

use App\Repository\BookRepository; // Import BookRepository

/**
 * Controller class that produces routers returning json objects
 */
class MyJsonController extends AbstractController
{
    /**
     * @var RouterInterface $router represents all routes in the application
     */
    public RouterInterface $router;

    /**
     * controller initiate public $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Router displays page with links to all json-routes
     * 
     * @return Response : redirect to the twig html template displaying links to all
     * jason-routes
     */
    #[Route('/json1', name: "api_landing")]
    public function apiLadning(): Response
    {
        //return new Response('Hello from the apiLadning() method!');
        return $this->render('myreport/json.html.twig');
    }

    /**
     * Router display all routes in the application in form of json
     * 
     * @param KernelInterface $kernel
     * @return Response : all routes in the application and their functions in form of json
     */
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

    /**
     * Route display a rundome quote in form of json-content
     * @param KernelInterface $kernel
     * @return Response content with a rundome quote in form of json
     */
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

        return $response;
    }

    // /**
    //  * Route display desk of cards in form json-content
    //  * @param SessionInterface $session
    //  * @return Response content with desk of cards in form of json
    //  */
    // #[Route('/api/desk', name: "api_desk", methods:['GET'])]
    // public function apiDesk(
    //     SessionInterface $session
    // ): Response {
    //     //$data = []; If api/desk does not work uncomment this
    //     try {
    //         $data = $session->get('desk');
    //         if ($data == null || count($data) < 52) {
    //             $newDesk = new Desk();
    //             $data = $newDesk->getDesk();
    //         }
    //     } catch (Exception $e) {
    //         $desk = new Desk();
    //         $data = $desk->getDesk();
    //     }

    //     $session->set('desk', $data);

    //     $response = new Response();
    //     $response->setContent(json_encode($data));

    //     return $response;
    // }

    // /**
    //  * Route create new desk of cards, shuffle it and display it in form json-content
    //  * 
    //  * @return Response content with desk of cards in form of json
    //  */
    // #[Route('/api/desk/shuffle', name: "api_desk_shuffle", methods:['POST'])]
    // public function apiShuffleDesk(): Response
    // {
    //     $desk = new Desk();
    //     $desk->getDesk();
    //     $desk->shuffleDesk();

    //     $data = $desk->getDesk();
    //     $response = new Response();

    //     $response->setContent(json_encode($data));
    //     $response->headers->set('Content-Type', 'application/json');

    //     return $response;
    // }

    // /**
    //  * Route create new desk of cards, shuffle it and display it in form json-content
    //  * 
    //  * @param  SessionInterface $session contans desk of cards
    //  * @return Response content with drown cards and the number of rest cards in the desk
    //  * in form of json
    //  */
    // #[Route('/api/desk/draw', name: "api_desk_draw", methods:['POST'])]
    // public function apiDrawDesk(
    //     SessionInterface $session
    // ): Response {
        
    //     $data = $session->get('desk');
    //     $cards = $session->get('drawed');
    //     if ($data == null || count($data) < 52) {
    //         $desk = new Desk();
    //         $data = $desk->getDesk();
    //     }
        
    //     $element = array_rand($data);
    //     $card = $data[$element];
    //     unset($data[$element]);
    //     $number = count($data);
    //     $result = [
    //         'card' => $card,
    //         'number' => $number,
    //     ];

    //     $session->set('drawed', $cards);
    //     $session->set('desk', $data);
    //     $response = new Response();
    //     $response->setContent(json_encode($result));
    //     $response->headers->set('Content-Type', 'application/json');

    //     return $response;
    // }

    // /**
    //  * Route retrieves desk of card from the session
    //  * if the desk empty, creates new desk of cards,
    //  * take given number of cards (rundomised) from te desk
    //  * saves result in the session
    //  * and display drown cards and number of cards in the desk as json-content
    //  * 
    //  * @param  SessionInterface $session contans desk of cards
    //  * @param Request $request request
    //  * @return Response content with drown cards and the number of rest cards in the desk
    //  * in form of json
    //  */
    // #[Route('api/deck/draw/{num_card}', name: "api_desk_draw_flera", methods:['POST'])]
    // public function apiDrawFleraDesk(
    //     SessionInterface $session,
    //     Request $request
    // ): Response {
    //     $response = new Response();
    //     $numCard = $request->request->get('num_card');
    //     $data = [];
    //     $hand = [];

    //     $data = $session->get('desk');
    //     if ($data == null) {
    //         $desk = new Desk();
    //         $data = $desk->getDesk();
    //     }

    //     if ( count($data) < $numCard) {
    //         $numCard = count($data);
    //     }

    //     for($i = 0; $i < $numCard; $i++) {
    //         $element = array_rand($data);
    //         $card = $data[$element];
    //         $hand[] = $card;
    //         unset($data[$element]);
    //     }
    //     $number = count($data);
    //     $result = [
    //         'drown' => $hand,
    //         'number' => $number,
    //     ];

    //     $session->set('desk', $data);
    //     $session->set('hand', $hand);
    //     $response->setContent(json_encode($result));
    //     $response->headers->set('Content-Type', 'application/json');

    //     return $response;
    // }

    // /**
    //  * Route deals chosen amount of cards to the chosen
    //  * amount of players from th desk saved in the session
    //  * if the desk empty, creates new desk of cards,
    //  * take given number of cards (rundomised) from te desk
    //  * saves result in the session
    //  * and display drown cards and number of cards in the desk as json-content
    //  * 
    //  * @param  SessionInterface $session contans desk of cards
    //  * @param int $play amount of players
    //  * @param int $cards amount of cards
    //  * @return Response content with cardes dealed for each player and
    //  * the number of rest cards in the desk
    //  * in form of json
    //  */
    // #[Route('api/deck/deal/{play}/{cards}', name: "api_desk_deal", methods:['POST'])]
    // public function apiDealCard(
    //     //SessionInterface $session,
    //     int $play,
    //     int $cards
    // ): Response {
    //     $desk = new Desk();
    //     $players = [];
    //     $response = new Response();

    //     $desk->shuffleDesk();
    //     for($k = 0; $k < $play; $k++) {
    //         $hand = [];
    //         for($i = 0; $i < $cards; $i++) {
    //             $card = $desk->randCard();
    //             $hand[] = $card;
    //         }
    //         $players[(string)($k + 1)] = $hand;
    //     }

    //     $number = $desk->countDesk();
    //     $result = [
    //         'players' => $players,
    //         'number cards left' => $number,
    //     ];

    //     $response = new Response();
    //     $response->setContent(json_encode($result));
    //     $response->headers->set('Content-Type', 'application/json');

    //     return $response;
    // }

    // /**
    //  * Route displays all variables saved in the session as json-content
    //  * 
    //  * @param  SessionInterface $session contans desk of cards
    //  * @return JsonResponse content with drown cards and the number of rest cards in the desk
    //  * in form of json
    //  */
    // #[Route('/session', name: 'get_session')] // get all frome session
    // public function apiGetSession(
    //     SessionInterface $session
    // ): JsonResponse {
    //     $data = [];

    //     foreach ($session->all() as $key => $value) {
    //         $data[$key] = $value;
    //     }

    //     // Return the session data as JSON
    //     return $this->json($data);
    // }
}
