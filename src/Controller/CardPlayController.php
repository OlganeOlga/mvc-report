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
use InvalidArgumentException;

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
        return $this->render('cardplay/desk.html.twig', ['data' => $desk]);
    }

    #[Route("/card/deck/suffle", name: "shuffle_card")]
    public function shufle(
        SessionInterface $session
    ): Response {
        $newDesk = new Desk();
        $desk = $newDesk->getDesk();

        shuffle($desk);
        $session->set('desk', $desk);
        $session->set('cards', []);
        return $this->render('cardplay/desk.html.twig', ['data' => $desk]);
    }

    #[Route("card/desk/draw", name: "draw_card")]
    public function draw(
        SessionInterface $session
    ): Response {
        $desk = $session->get('desk');
        if($desk == null) {
            $desk = new Desk();
            $desk->getDesk();
        }
        $cards = $session->get('cards');

        $element = array_rand($desk);
        $card = $desk[$element];
        $cards[] = $card;
        unset($desk[$element]);
        $number = count($desk);
        $data = [
            'desk' => $desk,
            'cards' => $cards,
            'number' => $number,
        ];

        $session->set('desk', $desk);
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
            //throw new \InvalidArgumentException($exception);
            throw new InvalidArgumentException($exception);
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
            'desk' => $desk,
        ];

        $session->set('desk', $desk);
        $session->set('cards', $cards);
        return $this->render('cardplay/draw_many.html.twig', $data);
    }

    #[Route('card/deck/deal/{player}/{cards}', name: 'deal_cards')]
    public function dealCards(
        int $player,
        int $cards
    ): Response {
        $newDesk = new Desk();
        $desk = $newDesk->getDesk();

        $players = [];

        for($p = 0; $p < $player; $p++) {
            $one = [];
            for($i = 0; $i < $cards; $i++) {
                $element = array_rand($desk);
                $card = $desk[$element];
                $one[] = $card;
                unset($desk[$element]);
            }
            $players[] = [$p + 1, $one];
        }

        $number = count($desk);
        $data = [
            'desk' => $desk,
            'PlayersHands' => $players,
            'numberPlayers' => $player,
            'cardsInDesk' => $number,
        ];
        return $this->render('cardplay/deal_card.html.twig', $data);
    }
}
