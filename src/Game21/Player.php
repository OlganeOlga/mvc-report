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
        $this->hand = new Hand();
        $this->bet = 0;
        $this->profit = 0;
        $this->status = "start";// status chenges in the game
    }

    /**
     *
     * @param CardGraphics $card
     */
    public function getCard(CardGraphics $card): void
    {
        $this->hand->addCard($card);
    }

    /**
     *
     * @return array<array<array<int>, int, int>>
     */
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

    /**
     *
     * @param array{hand: array<int, array{0: int, 1: int}>, bet: int, set: int} $arr
     */
    public function set(?array $arr): void
    {
        $hand = new Hand();

        $hand = new Hand();
        if (isset($arr['hand']) !== null) {
            $hand->set($arr['hand']);
        }
        $this->hand = $hand;
        $this->bet = $arr['bet'] ?? 0;
        $this->profit = $arr['profit'] ?? 0;
    }

    /**
     *
     * @return int points
     */
    public function points(): int
    {
        return $this->hand->getPoints();
    }

    /**
     *
     * @param int bet
     */
    public function doBet(int $bet): void
    {
        $this->bet = $bet;
    }

    /**
     *
     * @return int bet
     */
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

    /**
     *
     * @return array<array<array<int>, int, int>>
     */
    public function getHand(): array
    {
        return $this->hand->toString();
    }
}
