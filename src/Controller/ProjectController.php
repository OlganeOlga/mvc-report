<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;


use App\BlackJack\CardGraphics;
use App\BlackJack\Bank;
use App\BlackJack\Desk;
use App\BlackJack\Player;
use App\BlackJack\Game;

class ProjectController extends AbstractController
{
    /**
     * @var Game $game represents game used in the controller
     */
    private Game $game;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->game = new Game();
    }

    /**
     * Route is the start route for the project in cours MVC
     * 
     * @return Response
     */
    #[Route("/proj", name: "project")] // Start route for Game
    public function project(
        SessionInterface $session
    ): Response
    {
        $session->clear();
        return $this->render('project/home.html.twig');
    }


    /**
     * Route is the start route for the project in cours MVC
     * 
     * @return Response
     */
    #[Route("/proj/about", name: "aboutProject")] // Start route for Game
    public function aboutProject(): Response
    {
        //$session->clear();
        // $this->game->toSession($session);
        return $this->render('project/about.html.twig');
    }

    /**
     * Route get random card from the 52 possible
     * @return Response
     */
    #[Route("/proj/oneCard", name: "getOneCard")] // Start route for Game
    public function getOneCard(): Response
    {
        $cardRand = new CardGraphics;
        $cardRand->getRandom();
        $cardUrl = $cardRand->getUrl();
        //$session->clear();
        // $this->game->toSession($session);
        return $this->render('project/test/one.html.twig', ["url" => $cardUrl]);
    }

    /**
     * Route clear session, start play and save it in the sessoin
     * @return Response
     */
    #[Route("/proj/init", name: "initiatePlay")] // Start route for Game
    public function initPlay(
        SessionInterface $session
    ): Response
    {
        $session->clear();
        $data = [
            'desk' => $this->game->getDesk(),
        ];
        $session->set('game', $this->game);
        return $this->render('project/init.html.twig', $data);
    }

    /**
     * Route get random card from the 52 possible
     * @return Response
     */
    #[Route("/proj/getPlayers", name: "getPlayers", methods: ["POST"])] // Start route for Game
    public function getPlayers(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $number = $request->request->get('number');
        $players = [];
        for ($i = 1; $i <= $number; $i++) {
            $name = $request->request->get('player' . $i);
            $players[] = $name;
        }
        $this->game->shuffleDesk();
        $data = [
            'desk' => $this->game->getDesk(),
            'players' => $players,
            'bankBet' => $this->game->bankBet(),
        ];
        $session->set('names', $players);
        $this->game->shuffleDesk();
        $session->set('data', $data);
        $session->set('game', $this->game);
        return $this->redirectToRoute('playersSats');
    }

    /**
     * Show hhtml för players st satsa
     * @return Response
     */
    #[Route("/proj/satsa", name: "playersSats", methods: ["GET"])] // Start route for Game
    public function initPlayers(
        SessionInterface $session
    ): Response {
        $data = $session->get('data');
        return $this->render('project/showDesk.html.twig', $data);   
    }

    /**
     * Crete players, and deal first cards
     * @return Response
     */
    #[Route("/proj/playPost", name: "playersDecision", methods: ['POST'])]
    public function taPlayersSats(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $this->game = $session->get('game');
        $names = $session->get('names');  
        
        if ($this->game === null) {
            throw new \Exception('Game object is null');
        }
        $names = $session->get('names');
        if ($names === null) {
            throw new \Exception('Names array is null');
        }
        
        foreach($names as $name) {
            $player = new Player();
            $player->setName($name);
            $playerSats = $request->request->get($name);
            $player->doBet($playerSats);
            $this->game->addPlaying($name, $player);
        }
        
        $data = $this->game->firstDeal();
        $session->set('data', $data);
        $session->set('game', $this->game);
        return $this->redirectToRoute('playView');
    }

    /**
     * show game
     * 
     * @return Response
     */
    #[Route("/proj/play", name: "playView", methods: ['GET'])]
    public function playView(
        SessionInterface $session,
    ): Response
    {
        $data = $session->get('data');
        return $this->render('project/firstCards.html.twig', $data);
    }

    /**
     * Crete players, and deal first cards
     * @return Response
     */
    #[Route("/proj/action", name: "playersAction", methods: ['POST'])]
    public function playersAction(
        SessionInterface $session,
        Request $request,
    ): Response
    {
        $this->game = $session->get('game');
        $finish = $this->game->finish();

        if (!$finish[1]) {

            $action = $request->request->get('action');
            var_dump($action);
            $session->set('by action', $action);
            $name = $request->request->get('form_id');
            $player = $this->game->findPlayer($name, 'playing');
            
            switch($action) {
                case 'split': // split player and add it to the playing in the game
                    $newPlayer = new Player();
                    $playerData = $player->splitHand();
                    $newPlayer->setName($playerData['name']);
                    $newPlayer->doBet($playerData['bet']);
                    $newPlayer->getCard($playerData['card']);
                    $this->game->addPlaying($playerData['name'], $newPlayer); 
                    break;
                case 'insure':
                    $player->insure(); // insure player against black jack
                    break;
                case 'take_card':
                    $this->game->bankDeal($player, $name); // take card and see what happend
                    break;
                case 'ready':
                    $player->setStatus('ready'); // will be moved to ready array without winning
                    break;
                case 'wait':
                    $player->setStatus('wait'); // will be moved to ready array without winning
                    break;
                case '1:1':
                    $player->winGame(1, 1);
                    $player->setStatus('win');// will be moved to ready array with winning
                    break;
                case 'Black Jack':
                    $player->winGame(3, 2);// will be moved to ready array with winning
                    break;
            }

            $minCardByPlayer = $this->game->newCardToBank();
            $finish = $this->game->finish();
           
            if ($finish[1]) {
                $this->game->countWinst();
                $data = $this->game->getGame();
                $session->set('data', $data);
                $session->set('finish', $this->game->finish());
                return $this->redirectToRoute('finish');
            }
            $data = $this->game->getGame();
            $session->set('data', $data);
            $session->set('game', $this->game);
            return $this->redirectToRoute('playView');
        }
        $data = $this->game->getGame();
        $session->set('data', $data);
        $session->set('game', $this->game);
        return $this->redirectToRoute('finish');
    }

    /**
     * Crete players, and deal first cards
     * @return Response
     */
    #[Route("/proj/finish", name: "finish", methods: ['GET'])]
    public function gameFinish(
        SessionInterface $session,
    ): Response {
        $game = $session->get('game');
        $data = $game->getGame();
        return $this->render('project/gameFinish.html.twig', 
        ['datus' => $data]);
    }

}
