<?php

namespace App\Controller;

use App\Dice\Dice;
use App\Dice\GraphicDice;
use App\Dice\HandDice;
use InvalidArgumentException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class contain routes for game throw pig
 */
class DiceGameController extends AbstractController
{

    /**
     * public function create new die
     */
    public function createDie()
    {
        return new GraphicDice();
    }

    /**
     * Rolls chosen amount of dice
     * @param int $number number of dice to roll
     * 
     * @return array[int, string[]]
     */
    public function rollDices(int $number) {
        $diceRoll = [];
        for ($i = 1; $i <= $number; $i++) {
            $die = $this->createDie();
            $die->roll();
            $diceRoll[] = $die->getAsString();
        }

        $data = [
            "num_dices" => count($diceRoll),
            "diceRoll" => $diceRoll,
        ];

        return $data;
    }

    /**
     * Get hand and Rolls hand with chosen amount of dice
     * @param HandDice $hand empty hand of dice
     * @param int $number number of dice to roll
     * 
     * @return HandDice $hand
     */
    public function rollHandDices(HandDice $hand, int $number) {
        for ($i = 1; $i <= $number; $i++) {
            $i % 2 == 1 ? $hand->add(new GraphicDice()) : $hand->add(new Dice());
        }

        $hand->roll();
        return $hand;
    }
    
    /**
     * Start route for the pig game
     * @return Response
     */
    #[Route("/game/pig", name: "pig_start")]
    public function home(): Response
    {
        return $this->render('pig/home.html.twig');
    }

    /**
     * Route shows roll one dice and present result
     * @return Response
     */
    #[Route("/game/pig/test/roll", name: "test_roll_dice")]
    public function testRollDice(): Response
    {
        $data = $this->rollDices(1);

        return $this->render('pig/test/roll.html.twig', $data);
    }

    /**
     * Route rolls several dice and present result
     * 
     * @param int $num amount of the rolling dies,
     * between 1 and 12 includingly
     * @return Response
     */
    #[Route("/game/pig/test/roll/{number<\d+>}", name: "test_roll_num_dices")]
    public function testRollDices(int $number): Response
    {
        $data = $this->rollDices($number);

        return $this->render('pig/test/roll_many.html.twig', $data);
    }

    /**
     * Route shows roll one dice and present result
     * @return Response
     */
    #[Route("/game/pig/test/dicehand/{number<\d+>}", name: "test_dicehand")]
    public function testDiceHand(int $number): Response
    {
        $hand = new HandDice();

        $roledHand = $this->rollHandDices($hand, $number);

        $data = [
            "num_dices" => $hand->getNumberDices(),
            "diceRoll" => $hand->getString(),
        ];

        return $this->render('pig/test/dicehand.html.twig', $data);
    }

    /**
     * Route initiate pig game returns the page with formulär
     * @return Response
     */
    #[Route("/game/pig/init", name: "pig_init_get", methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('pig/init.html.twig');
    }

    /**
     * Get input from formulär and roll hand of dice
     * @param Request $request,
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game/pig/init", name: "pig_init_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $numDice = $request->request->get('num_dices');

        $hand = new HandDice();

        $roledHand = $this->rollHandDices($hand, $numDice);

        $session->set("pig_dicehand", $hand);
        $session->set("pig_dices", $numDice);
        $session->set("pig_round", 0);
        $session->set("game_total", 0);
        $session->set("round_total", 0);

        return $this->redirectToRoute('pig_play');
    }

    /**
     * Follow the pig playshows results of the furst steps
     * from Session
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game/pig/play", name: "pig_play", methods: ['GET'])]
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

    /**
     * Follow the pig playshows results of the furst steps
     * roll dice and save results in the session
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game/pig/roll", name: "pig_roll", methods: ['POST'])]
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

    /**
     * Saves results of the round in the session
     *
     * @param SessionInterface $session
     * 
     * @return Response
     */
    #[Route("/game/pig/save", name: "pig_save", methods: ['POST'])]
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
