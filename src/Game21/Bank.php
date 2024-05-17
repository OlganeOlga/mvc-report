<?php

namespace App\Game21;

use App\Game21\CardGraphics;
use App\Game21\Hand;

/**
 * Class repreents bank in the Play21
 *
 * Chaild class to Player
 */
class Bank extends Player
{
    /**
     * Composer is parent composer
     */
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
     * Take a random cards from the desk and add to the Bank hand.
     *
     * @param Desk    $desk    Desk object from which cards are dealt.
     * @return void
     */
    public function takeCards(Desk $desk): void
    {
        while($this->hand->getPoints() < 17) {
            $card = $desk->takeCard();
            $this->hand->addCard($card);
        }
    }
}
