<?php

namespace App\Game21;

use App\Game21\CardGraphics;

class Desk
{
    protected $play;

    public function __construct()
    {
        $this->play = [];
    }

    public function freshDesk()
    {
        for ($i = 0; $i < 4; $i++) {
            for ($k = 0; $k < 13; $k++) {
                $newCard = new CardGraphics();
                $newCard->set($k, $i); //set fase, sute
                $this->play[] = $newCard;
            };
        };
    }

    public function toArray(): array
    {
        $values = [];
        foreach ($this->play as $card) {
            $values[] = $card->toArray();
        }
        return $values;
    }

    public function set(
        array $arr = []
        )
    {
        $this->play = [];
        foreach ($arr as $c) {
            if (isset($c[0]) && isset($c[1])) {
                $card = new CardGraphics();
                $card->set($c[0], $c[1]);
                $this->play[] = $card;
            }
        }
    }

    public function getDesk(): array
    {
        $values = [];
        foreach ($this->play as $cort) {
            $cortstring = $cort->toString();
            $cortcolor = $cort->getCollor();
            $values[] = [$cortstring, $cortcolor];
        }
        return $values;
    }

    public function shuffleDesk()
    {
        shuffle($this->play);
    }

    public function takeCard(): CardGraphics
    {
        $randomKey = array_rand($this->play);
        $card = $this->play[$randomKey];
        unset($this->play[$randomKey]);
        
        return $card;
    }
}
