<?php

namespace App\Game21;

use App\Game21\CardGraphics;
use App\Game21\Hand;

class Player
{
    protected Hand $hand;
    protected int $bet;
    protected int $profit;
    protected string $status;

    public function __construct()
    {
        $this->hand = new Hand;
        $this->bet = 0;
        $this->profit = 0;
        $this->status = "start";// status chenges in the game
    }

    public function getCard(CardGraphics $card): void
    {
        $this->hand->addCard($card);
    }

    public function toArray(): array
    {
        $array = [
            'hand' => $this->hand !== null ? $this->hand->toArray() : null,
            'bet' => $this->bet,
            'profit' => $this->profit,
            //'status' => $this->status
        ];
        return $array;
    }

    public function set(array $arr): void
    {
        $hand = new Hand;
        
        $this->hand = $arr['hand'] !== null ? $hand->set($arr['hand']) : new Hand();
        $this->bet = $arr['bet'];
        $this->profit = $arr['profit'];
        //$this->status = $arr['status'];
    }

    public function points(): int
    {
        return $this->hand->getPoints();
    }

    public function doBet(int $bet): void
    {
        $this->bet = $bet;
    }

    public function getBet(): int
    {
        return $this->bet;
    }

    // public function win()
    // {
    //     $this->profit += $this->bet;
    // }

    // public function lose()
    // {
    //     $this->profit -= $this->bet;
    // }

    public function getHand(): array
    {
        return $this->hand->toString();
    }
}
