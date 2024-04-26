<?php

namespace App\Controller;

use App\Card\Card;

use App\Card\CardGraphics;
use App\Card\Desk;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class CardPlayController extends AbstractController
{
    #[Route("/session", name: "debug_cardplay")]
    public function debug(
        SessionInterface $session
    ): Response {
        // Hämta alla variabler i sessionen
        $data = $session->all();

        // Skriv ut eller logga variablerna
        //var_dump($data);
        return $this->render('cardplay/tests/debug.html.twig', ['data' => $data]);
    }

    #[Route("/session/delete", name: "delete_session")]
    public function delete(
        SessionInterface $session
    ): Response {
        $session->clear();
        $this->addFlash(
            'notice',
            'Nu är session raderad.'
        );

        $data = $session->all();
        return $this->render('cardplay/tests/debug.html.twig', ['data' => $data]);
    }

    #[Route("/card", name: "card_play")]
    public function home(
        SessionInterface $session
    ): Response {
        $desk = new Desk();
        $data = $desk->getDesk();
        $session->set('desk', $data);
        //$session->set('players', []);
        $session->set('cards', []);
        return $this->render('cardplay/home.html.twig');
    }

    #[Route("/card/desk/test/card", name: "one_card")]
    public function testCard(): Response
    {
        $oneCard = new CardGraphics();

        $data = [
            "card" => $oneCard->chose(),
            "cardString" => $oneCard->getAsString(),
            "color" => $oneCard->getCollor(),
        ];

        return $this->render('cardplay/tests/card.html.twig', $data);
    }

    #[Route("/card/desk", name: "desk_of_cards")]
    public function desk(
        SessionInterface $session
    ): Response {
        $desk = $session->get('desk');
        return $this->render('cardplay/tests/desk.html.twig', ['data' => $desk]);
    }

    #[Route("/card/deck/shuffle", name: "shuffle_card")]
    public function shufle(
        SessionInterface $session
    ): Response {
        $newDesk = new Desk();
        $desk = $newDesk->getDesk();
        $session->set('desk', $desk);

        shuffle($desk);
        $session->set('desk', $desk);
        //$session->set('players', []);
        $session->set('cards', []);
        return $this->render('cardplay/tests/desk.html.twig', ['data' => $desk]);
    }

    #[Route("card/desk/draw", name: "draw_card")]
    public function draw(
        SessionInterface $session
    ): Response {
        $desk = $session->get('desk');
        $cards = $session->get('cards');
        //$players = $session->get('players');

        $element = array_rand($desk);
        $cards[] = $desk[$element];
        unset($desk[$element]);
        $number = count($desk);
        $data = [
            'palyers' => $players,
            'cards' => $cards,
            'desk' => $desk,
            'number' => $number
        ];

        $session->set('desk', $desk);
        //$session->set('players', $players);
        $session->set('cards', $cards);

        return $this->render('cardplay/draw.html.twig', $data);
    }

    #[Route('/card/desk/draw/{num<\d+>}', name: 'draw_several_card')]
    public function drawNumber(
        int $num,
        SessionInterface $session
    ): Response {
        $exception = "Can not take more card than int the card desk!";
        $desk = $session->get('desk');
        $cards = $session->get('cards');
        $hand = [];

        if ($num < 1 || $num > count($desk)) {
            throw $exception;
        }

        for($i = 0; $i < $num; $i++) {
            $element = array_rand($desk);
            $card = $desk[$element];
            $cards[] = $card;
            $hand[] = $card;
            unset($desk[$element]);
        }

        $number = count($desk);
        $data = [
            'num' => $num,
            'hand' => $hand,
            'number' => $number,
        ];

        $session->set('desk', $desk);
        $session->set('cards', $cards);
        return $this->render('cardplay/draw_many.html.twig', $data);
    }


    #[Route('card/deck/deal/{nPlayers}/{nCards}', name: 'deal_cards')]
    public function dealCards(
        int $nPlayers,
        int $nCards,
        SessionInterface $session
    ): Response {
        // $desk = $session->get('desk');
        // // var_dump($desk);
        // $cards = $session->get('cards');
        // $players = [];
        $newDesk = new Desk();
        $desk = $newDesk->getDesk();
        for($p = 0; $p < $nPlayers; $p++) {
            $one = [];
            for($i = 0; $i < $nCards; $i++) {
                $element = array_rand($desk);
                $card = $desk[$element];
                //$cards[] = $card;
                $one[] = $card;
                unset($desk[$element]);
            }
            $players[] = [$p + 1, $one];
        }

        $number = count($desk);
        $data = [
            'PlayersHands' => $players,
            'numberPlayers' => $nPlayers,
            'cardsInDesk' => $number,
            'desk' => $desk
        ];

        // $session->set('desk', $desk);
        // $session->set('players', $players);
        // $session->set('cards', $cards);
        return $this->render('cardplay/deal_card.html.twig', $data);
    }
}
