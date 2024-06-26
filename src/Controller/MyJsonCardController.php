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

use App\Repository\BookRepository;

/**
 * Controller class that produces routers returning json objects
 */
class MyJsonCardController extends AbstractController
{
    /**
     * Route display desk of cards in form json-content
     * @param SessionInterface $session
     * @return Response content with desk of cards in form of json
     */
    #[Route('/api/desk', name: "api_desk", methods:['GET'])]
    public function apiDesk(
        SessionInterface $session
    ): Response {        
        $data = $session->get('desk');
        if ($data == null) {
            $newDesk = new Desk();
            $data = $newDesk->getDesk();
        }
        $session->set('desk', $data);

        // $response = new Response();
        // $response->setContent(json_encode($data));

        // return $response;
        return $this->json($data);
    }

    /**
     * Route create new desk of cards, shuffle it and display it in form json-content
     * 
     * @return Response content with desk of cards in form of json
     */
    #[Route('/api/desk/shuffle', name: "api_desk_shuffle", methods:['POST'])]
    public function apiShuffleDesk(): Response
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

    /**
     * Route extrakt from session/create new desk of cards,
     * draw one card and display result in json
     * 
     * @param  SessionInterface $session contans desk of cards
     * @return Response content with drown cards and the number of rest cards in the desk
     * in form of json
     */
    #[Route('/api/desk/draw', name: "api_desk_draw", methods:['POST'])]
    public function apiDrawDesk(
        SessionInterface $session
    ): Response {
        
        $data = $session->get('desk');
        $cards = $session->get('drawed');
        if ($data == null) {
            $desk = new Desk();
            $data = $desk->getDesk();
        }
        // if ($data == null || count($data) < 52) {
        //     $desk = new Desk();
        //     $data = $desk->getDesk();
        // }
        $element = array_rand($data);
        $card = $data[$element];
        unset($data[$element]);
        $number = count($data);
        $result = [
            'card' => $card,
            'number' => $number,
        ];

        $session->set('drawed', $cards);
        $session->set('desk', $data);
        $response = new Response();
        $response->setContent(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Route retrieves desk of card from the session
     * if the desk empty, creates new desk of cards,
     * take given number of cards (rundomised) from te desk
     * saves result in the session
     * and display drown cards and number of cards in the desk as json-content
     * 
     * @param  SessionInterface $session contans desk of cards
     * @param Request $request request
     * @return Response content with drown cards and the number of rest cards in the desk
     * in form of json
     */
    #[Route('api/deck/draw/{num_card}', name: "api_desk_draw_flera", methods:['POST'])]
    public function apiDrawFleraDesk(
        SessionInterface $session,
        Request $request
    ): Response {
        $numCard = $request->request->get('num_card');
        $data = [];
        $hand = [];

        $data = $session->get('desk');
        if ($data == null) {
            $desk = new Desk();
            $data = $desk->getDesk();
        }

        if ( count($data) < $numCard) {
            $numCard = count($data);
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
       return $this->json($result);
    }

    /**
     * Route deals chosen amount of cards to the chosen
     * amount of players from th desk saved in the session
     * if the desk empty, creates new desk of cards,
     * take given number of cards (rundomised) from te desk
     * saves result in the session
     * and display drown cards and number of cards in the desk as json-content
     *  
     * @param int $play amount of players
     * @param int $cards amount of cards
     * @return Response content with cardes dealed for each player and
     * the number of rest cards in the desk
     * in form of json
     */
    #[Route('api/deck/deal/{play}/{cards}', name: "api_desk_deal", methods:['POST'])]
    public function apiDealCard(
        int $play,
        int $cards
    ): Response {
        $desk = new Desk();
        $players = [];

        $desk->shuffleDesk();
        for($k = 0; $k < $play; $k++) {
            $hand = [];
            for($i = 0; $i < $cards; $i++) {
                $card = $desk->randCard();
                $hand[] = $card;
            }
            $players[(string)($k + 1)] = $hand;
        }

        $number = $desk->countDesk();
        $result = [
            'players' => $players,
            'number cards left' => $number,
        ];
        return $this->json($result);
    }

    /**
     * Route displays all variables saved in the session as json-content
     * 
     * @param  SessionInterface $session contans desk of cards
     * @return JsonResponse content with drown cards and the number of rest cards in the desk
     * in form of json
     */
    #[Route('/session', name: 'get_session')] // get all frome session
    public function apiGetSession(
        SessionInterface $session
    ): JsonResponse {
        $data = [];

        foreach ($session->all() as $key => $value) {
            $data[$key] = $value;
        }

        // Return the session data as JSON
        return $this->json($data);
    }
}
