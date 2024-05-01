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
     * Deal one card for each player in array.
     *
     * @param Desk    $desk    Desk object from which cards are dealt.
     * @param Player[] $players Array of Player objects.
     */
    public function dealCards(Desk $desk, array $players): void
    {
        foreach($players as $player) {
            $card = $desk->takeCard();
            $player->getCard($card);
        }
    }

    /**
     * TakeCards method description.
     *
     * @param Desk    $desk    Desk object from which cards are dealt.
     * @return void.
     */
    public function takeCards(Desk $desk): void
    {
        while($this->hand->getPoints() < 17) {
            $card = $desk->takeCard();
            $this->hand->addCard($card);
        }
    }
}
