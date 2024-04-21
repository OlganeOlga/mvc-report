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
                $new_card = new CardGraphics;
                $new_card->set($k, $i);
                $this->play[] = $new_card;
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

    // public function getDeskArray(): array
    // {
    //     return $this->play;
    // }

    public function shufleDesk(): array{
        return shuffle($this->play);
    }

}