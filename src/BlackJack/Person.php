<?php

namespace App\BlackJack;

use App\BlackJack\CardGraphics;
use App\BlackJack\Hand;

/**
 * Represents a player in the game.
 *
 * Each player has a hand of cards, a betPeng amount, and a profit value.
 */
class Person
{
    /**
     * @var string The name of the player.
     */
    protected string $name;
    /**
     * @var Hand The hand of cards held by the player.
     */
    protected Hand $hand;

    /** @var int The betPeng amount placed by the player. */
    protected int $betPeng;

    /**
     * @var string show status on the basis of the cards and points
     * in the hand
     */
    protected string $status;

    /** @var float The profit earned by the player. */
    protected float $profit;

    /** @var bool Does player has BlackJack. */
    protected bool $blackJack;

    /**
     * Constructor method that initializes a player with an empty hand, zero betPeng, and zero profit.
     */
    public function __construct()
    {
        $this->name = '';
        $this->hand = new Hand();
        $this->betPeng = 0;
        $this->profit = 0;
        $this->status = 'play';
        $this->blackJack = false;
    }
    /**Ca
     * Retrieves the name of the player.
     *
     * @return string players name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Cjange the name of the player.
     * @param string $name 
     * @return void
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Retrieves the current betPeng amount of the player.
     *
     * @return int The current betPeng amount.
     */
    public function getBet(): int
    {
        return $this->betPeng;
    }

    /**
     * Retrieves the profit earned by the player.
     *
     * @return float The profit earned by the player.
     */
    public function getProfit(): float
    {
        return $this->profit;
    }

    /**
     * Retrieves the current status of the player.
     *
     * @return string The current betPeng amount.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * change status of the player.
     * 
     * @param string $newStatus chnaged status of the player
     * @return string The current betPeng amount.
     */
    public function setStatus($newStatus): string
    {
        $this->status = $newStatus;
        return $this->status;
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
    * Sets the betPeng amount of the player.
    *
    * @param int $betPeng The betPeng amount to be set.
    * @return void
    */
    public function doBet(int $betPeng): void
    {
        $this->betPeng = $betPeng;
    }

    /**
     * Adds a card to the player's hand and return status of the player.
     *
     * @param CardGraphics $card The card to be added to the hand.
     * @return int players points
     */
    public function getCard(CardGraphics $card): int
    {
        $this->hand->addCard($card);
        $points = $this->points();
        return $points;
    }

    /**
     * Count cards in hand of player
     * @return int
     */
    public function countCards(): int 
    {
        return count($this->hand->getHand());
    }

    /**
     * Test if player get BlackJack
     * @return bool
     */
    public function blackJack(): bool
    {
        if($this->countCards() === 2 && $this->points() === 21) {
            $this->blackJack = true;
            return $this->blackJack;
        }
        return $this->blackJack;
    }

    
}
