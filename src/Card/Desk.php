<?php

namespace App\Card;

use App\Card\CardGraphics;

class Desk
{
    private $play = [];

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

    public function getDesk(): array
    {
        $values = [];
        foreach ($this->play as $cort) {
            $cortstring = $cort->getAsString();
            $cortcolor = $cort->getCollor();
            $values[] = [$cortstring, $cortcolor];
        }
        return $values;
    }

    public function getDeskArray(): array
    {
        return $this->play;
    }

    public function toArray(): array
    {
        $intArray = [];
        foreach ($this->play as $cort) {
            $intArray[] = $cort.toArray();
        }
        return $intArray;
    }

    public function setDesk($arr): object
    {
        $play = [];
        for ($i = 0; $i < $arr.length(); $i++) {
            $card = new CardGraphics;
            $card.set($arr[$i][0], $arr[$i][1]);
            $play[] = $card;
        }
        $this->play = $play;
        return $this->play;
    }

    public function shuffleDesk(): void
    {
        shuffle($this->play);
    }

}
