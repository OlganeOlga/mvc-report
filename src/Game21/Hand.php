<?php

namespace App\Game21;

use App\Game21\CardGraphics;

class Hand
{
    protected $cards;

    protected $points;

    public function __construct()
    {
        $this->cards = [];

        $this->points = 0;

        $this->hand = [$this->cards, $this->points];
    }

    public function addCard(CardGraphics $card)
    {
        $this->cards[] = $card;
        $this->points += $card->getValue();
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function toArray(): array
    {
        $cardArray = [];
        foreach($this->cards as $c) {
            $cardArray[] = $c->toArray();
        }

        $array = [
            'points' => $this->points,
            'cards' => $cardArray,
        ];
        return $array;
    }

    public function toString(): array
    {
        $cardArr = [];
        if (!empty($this->cards)) {
            foreach ($this->cards as $card) {
                $cardArr[] = [$card->toString(), $card->getCollor()];
            }
        }
        return $cardArr;
    }

    public function getHand(): array
    {
        $cardArr = [];
        if (!empty($this->cards)) {
            foreach ($this->cards as $card) {
                $cardArr[] = [$card->toString(), $card->getCollor()];
            }
        }
        return $cardArr;
    }


    public function set(array $handArr): self
    {
        $this->cards = [];
        foreach ($handArr['cards'] as $c) {
            $card = new CardGraphics;
            $this->cards[] = $card->set($c[0], $c[1]);
        }
        $this->points = $handArr['points'];

        return $this;
    }
}
