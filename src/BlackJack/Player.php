<?php

namespace App\BlackJack;

use App\BlackJack\CardGraphics;
use App\BlackJack\Hand;

/**
 * Represents a player in the game.
 *
 * Each player has a hand of cards, a betPeng amount, and a profit value.
 */
class Player extends Person
{
    // /**
    //  * @var string The name of the player.
    //  */
    // protected string $name;
    // /**
    //  * @var Hand The hand of cards held by the player.
    //  */
    // protected Hand $hand;

    // /** @var int The betPeng amount placed by the player. */
    // protected int $betPeng;

    // /**
    //  * @var string show status on the basis of the cards and points
    //  * in the hand
    //  */
    // protected string $status;

    // /** @var int The profit earned by the player. */
    // protected int $profit;

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
        parent::__construct();
        
        $this->insurance = false;
        $this->split = false;
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
}
