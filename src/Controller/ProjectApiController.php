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

class ProjectApiController extends AbstractController
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
    #[Route("/proj/api", name: "projectApi")] // Start route for Game
    public function project(
        // SessionInterface $session
    ): Response
    {
        return $this->render('project/api/home.html.twig');
    }


}
