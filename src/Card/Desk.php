<?php

namespace App\Card;

use App\Card\CardGraphics;

class Desk
{
    private array $play;

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
     * @return  array<int, array<int, mixed>>
     */
    public function getDesk(): array
    {
        $values = [];
        foreach ($this->play as $cart) {
            $cartstring = $cart->getAsString();
            $cartcolor = $cart->getCollor();
            $values[] = [$cartstring, $cartcolor];
        }
        return $values;
    }

    /**
     * @return array<array<CardGraphics>>
     */
    public function getDeskArray(): array
    {
        return $this->play;
    }

    /**
     * @return array<App\Card\CardGraphics|array<int, int>>
     */
    public function toArray(): array
    {
        $intArray = [];
        foreach ($this->play as $card) {
            $intArray[] = $card->toArray();
        }
        return $intArray;
    }

    /**
     * @param array<array<int, int>>
     * @return  array<int<0, max>, App\Card\CardGraphics>
     */
    public function setDesk($arr): array
    {
        $play = [];
        for ($i = 0; $i < count($arr); $i++) {
            $card = new CardGraphics();
            $card->set($arr[$i][0], $arr[$i][1]);
            $play[] = $card;
        }
        $this->play = $play;
        return $this->play;
    }

    /**
     * @return bool
     */
    public function shufleDesk(): bool
    {
        return shuffle($this->play);
    }


}