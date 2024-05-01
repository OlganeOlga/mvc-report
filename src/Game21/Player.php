<?php

namespace App\Game21;

use App\Game21\CardGraphics;
use App\Game21\Hand;

class Player
{
    protected Hand $hand;
    protected int $bet;
    protected int $profit;

    public function __construct()
    {
        $this->hand = new Hand;
        $this->bet = 0;
        $this->profit = 0;
    }

    /**
     * GetBet method description
     *
     * @return int.
     */
    public function getBet(): int
    {
        return $this->bet;
    }

     /**
     * GetPoints method description
     *
     * @return int.
     */
    public function getProfit(): int
    {
        return $this->profit;
    }

     /**
     * GetBet method description
     * @param CardGraphics $card
     * @return void.
     */
    public function getCard(CardGraphics $card): void
    {
        $this->hand->addCard($card);
    }

    /**
     * ToArray method description
     *
     * @return array<string, mixed>.
     */
    public function toArray(): array
    {
        $array = [
            'hand' => $this->hand->toArray(),
            'bet' => $this->bet,
            'profit' => $this->profit,
        ];
        return $array;
    }

    /**
     * ToArray method description
     *
     * @param array<string, mixed> $arr represents variables to set hand
     * @return void.
     */
    public function set(array $arr): void
    {
        $hand = new Hand;
        
        $this->hand = $arr['hand'] !== null ? $hand->set($arr['hand']) : new Hand();
        $this->bet = $arr['bet'];
        $this->profit = $arr['profit'];
    }

    /**
     * Points method description
     *
     * @return int points of the hand.
     */
    public function points(): int
    {
        return $this->hand->getPoints();
    }

    /**
     * Points method description
     * @param int $bet the bet of the player
     * @return void.
     */
    public function doBet(int $bet): void
    {
        $this->bet = $bet;
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
     * getHand method description
     *
     * @return array<string[]> string representation of the hand.
     */
    public function getHand(): array
    {
        return $this->hand->toString();
    }
}
