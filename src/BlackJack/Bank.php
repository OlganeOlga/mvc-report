<?php

namespace App\BlackJack;

// use App\BlackJack\CardGraphics;
// use App\BlackJack\Hand;

/**
 * Class repreents bank in the BlackJack
 *
 * Chaild class to Person
 */
class Bank extends Person
{
    /**
     * Composer is parent composer
     */
    public function __construct()
    {
        parent::__construct();
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
            'blackJack' => $this->blackJack,
            'profit' => $this->profit,

        ];
        return $hand;
    }

    /**
     * Deal one card for each player in array.
     *
     * @param Desk $desk Desk object from which cards are dealt.
     * @param Player $player Player object.
     * 
     * @return int resulting players points
     */
    public function dealCards(Desk $desk, $player): int
    {
        $card = $desk->takeCard();
        $points = $player->getCard($card);

        if($points > 21) {
            $player->setStatus('fat');
        } elseif ($player->blackJack()) {
            $player->setStatus('Black Jack');
        } elseif (!$player->blackJack() && $points === 21) {
            $player->setStatus('21');
        }
        return $points;
    }

    /**
     * Take card from desk
     * change status
     * 
     * @return bool
     */
    public function takeCard(Desk $desk): bool
    {
        if ($this->points() < 17) {
            $card = $desk->takeCard();
            // override players getCard
            $this->getCard($card);
            $points = $this->points();
          // Check for blackjack or win first
            if ($this->blackJack()) {
                $this->setStatus('Black Jack');
                return false;
            } elseif (!$this->blackJack() && $points === 21) {
                $this->setStatus('21');
                return false;
            } elseif ($points > 21) {
                $this->setStatus('fat');
                return false;
            } elseif ($points > 17) {
                $this->setStatus('ready');
                return false;
            }
            return true;
        }
        return false;
    }
}
