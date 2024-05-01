<?php

namespace App\Game21;

use App\Game21\CardGraphics;
use App\Game21\Hand;

/**
 * Represents a player in the game.
 * 
 * Each player has a hand of cards, a bet amount, and a profit value.
 */
class Player
{
    /** 
     * @var Hand The hand of cards held by the player. 
     */
    protected Hand $hand;

    /** @var int The bet amount placed by the player. */
    protected int $bet;

    /** @var int The profit earned by the player. */
    protected int $profit;

    /**
     * Constructor method that initializes a player with an empty hand, zero bet, and zero profit.
     */
    public function __construct()
    {
        $this->hand = new Hand;
        $this->bet = 0;
        $this->profit = 0;
    }

    /**
     * Retrieves the current bet amount of the player.
     *
     * @return int The current bet amount.
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * Retrieves the profit earned by the player.
     *
     * @return int The profit earned by the player.
     */
    public function getProfit(): int
    {
        return $this->profit;
    }

    /**
     * Adds a card to the player's hand.
     *
     * @param CardGraphics $card The card to be added to the hand.
     * @return void
     */
    public function getCard(CardGraphics $card): void
    {
        $this->hand->addCard($card);
    }

    /**
     * Converts the player's data to an associative array.
     *
     * @return array<string, mixed> An associative array representing the player's data.
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
     * Sets the player's data based on the provided array.
     *
     * @param array<string, mixed> $arr An array containing the player's data.
     * @return void
     */
    public function set(array $arr): void
    {
        $hand = new Hand;
        
        $this->hand = $arr['hand'] !== null ? $hand->set($arr['hand']) : new Hand();
        $this->bet = $arr['bet'];
        $this->profit = $arr['profit'];
    }

    /**
     * Calculates and returns the total points of the player's hand.
     *
     * @return int The total points of the player's hand.
     */
    public function points(): int
    {
        return $this->hand->getPoints();
    }

    /**
    * Sets the bet amount of the player.
    *
    * @param int $bet The bet amount to be set.
    * @return void
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
     * Retrieves the array<string[]> representation of the player's hand.
     *
     * @return array<string[]> The string representation of the player's hand.
     */
    public function getHand(): array
    {
        return $this->hand->toString();
    }
}
