<?php

namespace App\Controller;

use App\Card\Card;

use App\Card\CardGraphics;
use App\Card\Desk;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class CardPlayController extends AbstractController
{
    #[Route("/session", name: "debug_cardplay")] 
    public function debug(
        Request $request,
        SessionInterface $session
    ): Response 
    {
        // Hämta alla variabler i sessionen
        $data = $session->all();

        // Skriv ut eller logga variablerna
        //var_dump($data);
        return $this->render('cardplay/tests/debug.html.twig', ['data'=>$data]);
    }

    #[Route("/session/delete", name: "delete_session")] 
    public function delete(
        SessionInterface $session
    ): Response 
    {
        $session->clear();
        $this->addFlash(
            'notice',
            'Nu är session raderad.'
        );

        $data = $session->all();
        return $this->render('cardplay/tests/debug.html.twig', ['data'=>$data]);
    }

    #[Route("/card", name: "card_play")]
    public function home(
        SessionInterface $session
    ): Response
    {
        $desk = new Desk();
        $data = $desk->getDesk();
        $session->set('desk', $data);
        $session->set('players', []);
        return $this->render('cardplay/home.html.twig');
    }

    #[Route("/card/desk/test/card", name: "one_card")]
    public function testCard(): Response
    {
        $oneCard = new CardGraphics();

        $data = [
            "card" => $oneCard->chose(),
            "cardString" => $oneCard->getAsString(),
            "color" =>$oneCard->getCollor(),
        ];

        return $this->render('cardplay/tests/card.html.twig', $data);
    }

    #[Route("/card/desk", name: "desk_of_cards")]
    public function desk(
        SessionInterface $session
    ): Response
    {
        $desk = $session->get('desk');
        return $this->render('cardplay/tests/desk.html.twig', ['data'=>$desk]);
    }

    #[Route("card/deck", name: "shufle_card")]
    public function shufle(
        SessionInterface $session
    ): Response
    {
        $desk = $session->get('desk');

        if (count($desk) < 52) {
            $newDesk = new Desk();
            $desk = $newDesk->getDesk();
            $session->set('desk', $desk);
        }

        shuffle($desk);
        $session->set('desk', $desk);
        $session->set('players', []);
        return $this->render('cardplay/tests/desk.html.twig', ['data'=>$desk]);
    }

    #[Route("card/desk/draw", name: "draw_card")]
    public function draw(
        SessionInterface $session
    ): Response
    {
        $desk = $session->get('desk');

        $element = array_rand($desk);
        $card = $desk[$element];
        unset($desk[$element]);
        $number = count($desk);
        $data = [
            'card'=> $card,
            'number'=> $number,
        ];

        $session->set('desk', $desk);

        return $this->render('cardplay/tests/draw.html.twig', $data);
    }

    #[Route('/card/desk/draw/{num<\d+>}', name: 'draw_several_card')]
    public function drawNumber(
        int $num,
        SessionInterface $session
    ): Response
    {
        $desk = $session->get('desk');
        $hand = [];

        if ($num < 1 || $num > count($desk)) {
            throw new \Exception("Can not take more card than int the card desk!");
        }

        for($i=0; $i<$num; $i++) {
            $element = array_rand($desk);
            $card = $desk[$element];
            $hand[] = $card;
            unset($desk[$element]);
        }

        $number = count($desk);
        $data = [
            'num'=> $num,
            'hand'=> $hand,
            'number'=> $number,
        ];

        $session->set('desk', $desk);
        return $this->render('cardplay/draw_many.html.twig', $data);
    }

    
    #[Route('card/deck/deal/{player}/{cards}', name: 'deal_cards')]
    public function DealCards(
        int $player,
        int $cards, 
        SessionInterface $session
    ): Response
    {
        $desk = $session->get('desk');
        // var_dump($desk[0]);
        $players = [];
        // if ($cards * $player > count($desk)) {
        //     throw new \Exception("Can not take more card than in the card desk!");
        // }

        // if ($cards < 1 || $player < 1) {
        //     throw new \Exception("May be you chose not enough cards or players");
        // }

        // if (empty($desk)) {
        //     throw new \Exception("Not enough cards in the card desk!");
        // }

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
            'PlayersHands'=> $players,
            'numberPlayers'=> $player,
            'cardsInDesk'=> $number,
        ];

        $session->set('desk', $desk);
        $session->set('players', $players);
        return $this->render('cardplay/deal_card.html.twig', $data);
    }
}