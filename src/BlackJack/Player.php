<?php

namespace App\BlackJack;

use App\BlackJack\CardGraphics;
use App\BlackJack\Hand;

/**
 * Represents a player in the game.
 *
 * Each player has a hand of cards, a betPeng amount, and a profit value.
 */
class Player
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

    /** @var int The profit earned by the player. */
    protected int $profit;

    /** @var bool Does player insuerd against banks BlackJack. */
    protected bool $insurance;

    /** @var bool Does player has BlackJack. */
    protected bool $blackJack;

    /** @var bool can player split hand. */
    protected bool $split;

    /**
     * Constructor method that initializes a player with an empty hand, zero betPeng, and zero profit.
     */
    public function __construct()
    {
        $this->name = '';
        $this->hand = new Hand();
        $this->betPeng = 0;
        $this->profit = 0;
        $this->status = "play";
        $this->insurance = false;
        $this->blackJack = false;
        $this->split = false;
    }
    /**
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
     * Retrieves insurance of the player.
     *
     * @return bool The currentinsurance.
     */
    public function insurance(): bool
    {
        return $this->insurance;
    }

    /**
     * Insuar PLAYER against banks BlackJack.
     *
     * @return void
     */
    public function insure(): void
    {
        $this->insurance = true;
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
     * @return int The profit earned by the player.
     */
    public function getProfit(): int
    {
        return $this->profit;
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
        $points = $this->hand->getPoints();
        if($points > 21) {
            $this->loosGame(1, 1);
            $this->status = 'fat';
        } elseif ($points === 21) {
            $this->blackJack();
            $this->status = 'wait';
        }
        return $points;
    }

    /**
     * Retrieves the current status of the player.
     *
     * @return string The current betPeng amount.
     */
    public function getStatus() {
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
    * Change the profit amount for the player.
    *
    * @param int $multiplicate multiplicate bet
    * @param int $devide divide bet
    * @return int $this->profit
    */
    public function winGame($multiplicate, $devide): int
    {
        $this->status = "win";
        $this->profit = $this->betPeng * $multiplicate / $devide;
        return $this->profit;
    }

    /**
    * Change the profit amount for the player.
    *
    * @param int $multiplicate multiplicate bet
    * @param int $devide divide bet
    * @return int $this->profit
    */
    public function loosGame($multiplicate, $devide): int
    {
        $this->setStatus("loos");
        $this->profit = - $this->betPeng * $multiplicate / $devide;
        return $this->profit;
    }

    /**
     * Retrieves the array<string[]> representation of the player's hand.
     *
     * @return array<string[]> The string representation of the player's hand.
     */
    public function getHand(): array
    {      
        $hand = [
            'bet' => $this->getBet(),
            'hand' => $this->hand->getHand(),
            'points' => $this->hand->getPoints(),
            'soft' => $this->hand->soft(),
            'status' => $this->status,
            'insure' => $this->insurance(),
            'blackJack' => $this->blackJack,
            'split' => $this->canSplit(),
            'profit' => $this->profit,

        ];
        return $hand;
    }

    /**
     * Test if player get BlackJack
     * @return bool
     */
    public function blackJack(): bool
    {
        $faces = $this->hand->getCardFaces();
        $expected = ['queen', 'jack', 'king', '10'];
        if(count($faces) === 2 && in_array('ace', $faces)) {
            foreach ($expected as $value){
                if(in_array($value, $faces)) {
                    $this->blackJack = true;
                    return $this->blackJack;
                }
            }
        }
        return $this->blackJack;
    }

    /**
     * Can player split hand?
     * 
     * @return bool
     */
    public function canSplit(): bool
    {
        $this->split = $this->hand->canSplit();
        return $this->split;
    }

    /**
     * split hand make copy self, add "-1" to the self name, 
     * give name self-2 to the new object
     * removes one card ajust points
     * gives another card it to the new player
     * 
     * @return array{name:string, bet:int, card:CardGraphics}|null
     */
    public function splitHand(): ?array
    {
        $name = $this->name;
        $betPeng = $this->betPeng;
        $card1 = $this->hand->split();
        if($card1){
        
            $this->name = $name . '-1';
            
            return [
                'name' => $name . '-2',
                'bet' => $betPeng,
                'card' => $card1,
            ];
        }
        return null;
    }

    /**
     * Count cards in hand of player
     * @return int
     */
    public function countCards(): int 
    {
        return count($this->hand->getHand());
    }
}
