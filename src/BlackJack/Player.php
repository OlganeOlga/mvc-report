<?php

namespace App\BlackJack;

// use App\BlackJack\CardGraphics;
// use App\BlackJack\Hand;

/**
 * Represents a player in the game chaild class to Person.
 *
 * Each player has a hand of cards, a betPeng amount, and a profit value.
 */
class Player extends Person
{

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
     * Overrid parent methods getCard
     * Adds a card to the player's hand and palyers points.
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
    * Change the profit amount for the player.
    *
    * @param int $multiplicate multiplicate bet
    * @param int $devide divide bet
    * @return float $this->profit
    */
    public function winGame($multiplicate, $devide): float
    {
        $this->status = 'win';
        $this->profit = floatval($this->betPeng) * $multiplicate / $devide;
        return $this->profit;
    }

    /**
    * Change the profit amount for the player.
    *
    * @param int $multiplicate multiplicate bet
    * @param int $devide divide bet
    * @return float $this->profit
    */
    public function loosGame($multiplicate, $devide): float
    {
        $this->setStatus('loos');
        $this->profit = - floatval($this->betPeng) * $multiplicate / $devide;
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
            'points' => $this->points(),
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
     * split hand make copy self, add '-1' to the self name, 
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
