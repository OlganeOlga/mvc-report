<?php

namespace App\Card;

use App\Card\CardGraphics;

class Desk
{
    /** @var int[] */
    private array $play = [];

    public function __construct()
    {
        for ($i = 0; $i < 4; $i++) {
            for ($k = 0; $k < 13; $k++) {
                $newCard = new CardGraphics();
                $newCard->set($k, $i);
                $this->play[] = $newCard;
            };
        };
    }

    /**
     * GetDesk method description
     *
     * @return array<string[]> Array contains strings: card reprresentation and color
     */
    public function getDesk(): array
    {
        $values = [];
        foreach ($this->play as $card) {
            $cardstring = $card->getAsString();
            $cardcolor = $card->getCollor();
            $values[] = [$cardstring, $cardcolor];
        }

        return $values;
    }

    /**
     * GetDeskArray method description.
     *
     * @return array<int> Array containing array representation of card elements.
     */
    public function getDeskArray(): array
    {
        return $this->play;
    }

    // public function setDesk($arr): object
    // {
    //     $play = [];
    //     for ($i = 0; $i < count($arr); $i++) {
    //         $card = new CardGraphics;
    //         $card.set($arr[$i][0], $arr[$i][1]);
    //         $play[] = $card;
    //     }
    //     $this->play = $play;
    //     return $this->play;
    // }

    public function shuffleDesk(): void
    {
        shuffle($this->play);
    }

}
