<?php

namespace App\Controller;

use App\Dice\Dice;
use App\Dice\GraphicDice;
use App\Dice\HandDice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DiceGameController extends AbstractController
{
    #[Route("pig/games", name: "games")]
    #[CustomAnnotation("Move to games.")]
    public function games(): Response
    {
        return $this->render('pig/games.html.twig');
    }

    #[Route("/game/pig", name: "pig_start")]
    #[CustomAnnotation("Start game pig.")]
    public function home(): Response
    {
        return $this->render('pig/home.html.twig');
    }

    #[Route("/game/pig/test/roll", name: "test_roll_dice")]
    #[CustomAnnotation("Game pig: Tests to roll one dice.")]
    public function testRollDice(): Response
    {
        $die = new GraphicDice();

        $data = [
            "dice" => $die->roll(),
            "diceString" => $die->getAsString(),
        ];

        return $this->render('pig/test/roll.html.twig', $data);
    }

    #[Route("/game/pig/test/roll/{num<\d+>}", name: "test_roll_num_dices")]
    #[CustomAnnotation("game pig: Tests to roll several.")]
    public function testRollDices(int $num): Response
    {
        if ($num > 12) {
            throw new \Exception("Can not roll more than 12 dices!");
        }

        $diceRoll = [];
        for ($i = 1; $i <= $num; $i++) {
            $die = new GraphicDice();
            $die->roll();
            $diceRoll[] = $die->getAsString();
        }

        $data = [
            "num_dices" => count($diceRoll),
            "diceRoll" => $diceRoll,
        ];

        return $this->render('pig/test/roll_many.html.twig', $data);
    }

    #[Route("/game/pig/test/dicehand/{num<\d+>}", name: "test_dicehand")]
    #[CustomAnnotation("Game pig: Tests to roll a hand dice.")]
    public function testDiceHand(int $num): Response
    {
        if ($num > 99) {
            throw new \Exception("Can not roll more than 99 dices!");
        }

        $hand = new HandDice();
        for ($i = 1; $i <= $num; $i++) {
            if ($i % 2 === 1) {
                $hand->add(new GraphicDice());
            } else {
                $hand->add(new Dice());
            }
        }

        $hand->roll();

        $data = [
            "num_dices" => $hand->getNumberDices(),
            "diceRoll" => $hand->getString(),
        ];

        return $this->render('pig/test/dicehand.html.twig', $data);
    }

    #[Route("/game/pig/init", name: "pig_init_get", methods: ['GET'])]
    #[CustomAnnotation("Game pig: initiates game.")]
    public function init(): Response
    {
        return $this->render('pig/init.html.twig');
    }

    #[Route("/game/pig/init", name: "pig_init_post", methods: ['POST'])]
    #[CustomAnnotation("Game pig: starts playing with given amount of hands and given amount of dice in each hand.")]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $numDice = $request->request->get('num_dices');

        $hand = new HandDice();
        for ($i = 1; $i <= $numDice; $i++) {
            $hand->add(new GraphicDice());
        }
        $hand->roll();

        $session->set("pig_dicehand", $hand);
        $session->set("pig_dices", $numDice);
        $session->set("pig_round", 0);
        $session->set("game_total", 0);
        $session->set("round_total", 0);

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/play", name: "pig_play", methods: ['GET'])]
    #[CustomAnnotation("Game pig: opens play.")]
    public function play(
        SessionInterface $session
    ): Response {
        $dicehand = $session->get("pig_dicehand");

        $data = [
            "pigDices" => $session->get("pig_dices"),
            "pigRound" => $session->get("pig_round"),
            "pigTotal" => $session->get("game_total"),
            "roundTotal" => $session->get("round_total"),
            "diceValues" => $dicehand->getString()
        ];

        return $this->render('pig/play.html.twig', $data);
    }

    #[Route("/game/pig/roll", name: "pig_roll", methods: ['POST'])]
    #[CustomAnnotation("Game pig: rolls dice in one hand.")]
    public function roll(
        SessionInterface $session
    ): Response {
        $hand = $session->get("pig_dicehand");
        $hand->roll();

        $roundTotal = $session->get("round_total");
        $round = 0;
        $values = $hand->getValues();
        foreach ($values as $value) {
            if ($value === 1) {
                $round = 0;
                $roundTotal = 0;


                $this->addFlash(
                    'warning',
                    'You got a 1 and you lost the round points!'
                );
                break;
            }
            $round += $value;
        }

        $session->set("pig_round", $round);
        $session->set("round_total", $roundTotal + $round);

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/save", name: "pig_save", methods: ['POST'])]
    #[CustomAnnotation("Game pig: saves the tirn in the game.")]
    public function save(
        SessionInterface $session
    ): Response {
        $roundTotal = $session->get("pig_round");
        $gameTotal = $session->get("game_total");

        $session->set("pig_round", 0);
        $session->set("game_total", $gameTotal + $roundTotal);

        $this->addFlash(
            'notice',
            'Your round was saved to the total!'
        );
        return $this->redirectToRoute('pig_play');
    }
}

