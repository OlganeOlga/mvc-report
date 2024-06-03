<?php

namespace App\BlackJack;

use App\BlackJack\CardGraphics;
use App\BlackJack\Hand;

/**
 * Class repreents bank in the BlackJack
 *
 * Chaild class to Player
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
     * @return int players points
     */
    public function dealCards(Desk $desk, $player): int
    {
        $card = $desk->takeCard();
        $playersPoints = $player->getCard($card);
        
        return $playersPoints;
    }

    public function takeCard(Desk $desk): bool
    {
        $bankPoints = $this->points();
        if ($bankPoints < 17) {
            $card = $desk->takeCard();
            // override players getCard
            $this->hand->addCard($card);

            $newPoints = $this->points();           // Check for blackjack or win first
            if ($newPoints === 21 && count($this->hand->getCardFaces()) === 2) {
                $this->status = 'Black Jack';
                return false;
            } elseif ($newPoints === 21 && count($this->hand->getCardFaces()) > 2) {
                $this->setStatus('win');
                return false;
            } elseif ($newPoints > 21) {
                $this->setStatus('fat');
                return false;
            }
            
            return true;
        }
        
        return false;
    }
}
