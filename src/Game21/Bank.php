<?php

namespace App\Game21;

use App\Game21\CardGraphics;
use App\Game21\Hand;

class Bank extends Player
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * deal one card for each player in array
     * @param {$desk} Desk object 
     * @param {palyers} array of objekts Player
     */
    public function dealCards(Desk $desk, array $players)
    {
        foreach($players as $player) {
            $card = $desk->takeCard();
            $player->getCard($card);
        }
    }

    public function takeCards(Desk $desk)
    {
        while($this->hand->getPoints() < 17) {
            $card = $desk->takeCard();
            $this->hand->addCard($card);
        }
    }
}
