<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Game21\Bank;
use App\Game21\Desk;
use App\Game21\Player;
use App\Game21\Game21;

class Game21gameController extends AbstractController
{
    /**
     * @var Game21 $game represents game used in the controller
     */
    private Game21 $game;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->game = new Game21();
    }

    /**
     * Route is the start route for all routes of cardplay 21
     * saves game in the session
     * 
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game", name: "gamekmom03")] // Start route for Game21
    public function games(SessionInterface $session): Response
    {
        $session->clear();
        $this->game->toSession($session);
        return $this->render('game21/home.html.twig');
    }

    /**
     * Route shows results of FirstState in the html.
     * 
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game/play", name: "play21")]
    public function startGame(SessionInterface $session): Response
    {
        $data = $this->game->firstState($session);
        return $this->render('game21/play.html.twig', $data);
    }

    /**
     * Route shows results of SecondState in the html.
     * 
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game/play-again", name: "followplay21")]
    public function followGame(SessionInterface $session): Response
    {
        $session->clear();
        $this->game->toSession($session);
        $data = $this->game->firstState($session);
        return $this->render('game21/play.html.twig', $data);
    }

    /**
     * Route shows bank bet in html.
     * 
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game21/bankBet", name: "bankBet_card21")] //Ask bank to do bet
    public function bankBet21(SessionInterface $session): Response
    {
        $data = $this->game->secondState($session);
        return $this->render('game21/player_bet.html.twig', $data);
    }

    /**
     * Route gets player bet from the form in html and first card of the palyer and
     * gives player a card.
     * 
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game21/playersBet", name: "player_bet21", methods: ['POST'])]
    public function playersBet(
        SessionInterface $session,
        Request $request
    ): Response {
        $bet = $request->request->get('playersBet');
        $data = $this->game->thirdState($session, $bet);
        return $this->render('game21/card_to_player.html.twig', $data);
    }

    /**
     * Route gets player bet from the form in html and first card of the palyer and
     * gives player a card and shows the posints
     * follow steps dependent on the players points.
     * 
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game21/cardToPlayer", name: "player_get_card21")] //
    public function cardToPlayer(
        SessionInterface $session
    ): Response {
        $data = $this->game->playerNewCard($session);
        if($data['Status'] == "fat player" || $data['Status'] == "player wins") {
            return $this->render('game21/endround.html.twig', $data);
        }
        return $this->render('game21/card_to_player.html.twig', $data);
    }

    /**
     * If player is satisfyed, this route shows all cards that get bank
     * 
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game21/cardToBank", name: "bank_get_cards21")]
    public function cardToBank(
        SessionInterface $session
    ): Response {
        $data = $this->game->bankGetCards($session);
        return $this->render('game21/endround.html.twig', $data);
    }

}
