<?php

namespace App\Game21;

use App\Game21\CardGraphics;

class Desk
{
    /** @var array<CardGraphics> */
    protected array $play;

    public function __construct()
    {
        $this->play = [];
    }

    /**
     * FreshDesk method description.
     *
     * @return array<CardGraphics> representation of whal (52) desk of cards in the play
     */
    public function freshDesk(): array
    {
        for ($i = 0; $i < 4; $i++) {
            for ($k = 0; $k < 13; $k++) {
                $newCard = new CardGraphics();
                $newCard->set($k, $i); //set fase, sute
                $this->play[] = $newCard;
            };
        };
        return $this->play;
    }

    /**
     * ToArray method description.
     *
     * @return array<int[]> array of integer representation all cards in the desk.
     */
    public function toArray(): array
    {
        $values = [];
        foreach ($this->play as $card) {
            $values[] = $card->toArray();
        }
        return $values;
    }

    /**
     * Set method description.
     *
     * @param array<int[]> $arr An array of integer arrays representing card values.
     * @return void
     */
    public function set(
        array $arr = []
        ): void
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

    /**
     * GetDesk method description.
     *
     * @return array<string[]> array of string representation of all cards in the desk.
     */
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
    
    /**
     * ShuffleDesk method description: shuffle all cards in the array
     * $this->play
     *
     * @return void
     */
    public function shuffleDesk(): void
    {
        shuffle($this->play);
    }

    /**
     * TakeCard method description.
     *
     * @return CardGraphics.
     */
    public function takeCard(): CardGraphics
    {
        $randomKey = array_rand($this->play);
        $card = $this->play[$randomKey];
        unset($this->play[$randomKey]);
        
        return $card;
    }
}
