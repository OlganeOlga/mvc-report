<?php

namespace App\Game21;

use App\Game21\CardGraphics;

class Hand
{
    protected array $cards;
    protected int $points;

    public function __construct()
    {
        $this->cards = [];
        $this->points = 0;
        //$this->hand = [$this->cards, $this->points];
    }

    /**
     *
     * @param CardGraphics $card
     */
    public function addCard(CardGraphics $card): void
    {
        $this->cards[] = $card;
        $this->points += $card->getValue();
    }

    /**
     *
     * @return int points
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     *
     * @return array<array<int>>
     */
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

    /**
     *
     * @return array<array<string>>
     */
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

    /**
     *
     * @return array<array<int>
     */
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

    /**
     * @param array<array<int>>
     * @return Hand $hand
     */
    public function set(array $handArr): self
    {
        $this->cards = [];
        foreach ($handArr['cards'] as $c) {
            $card = new CardGraphics();
            $this->cards[] = $card->set($c[0], $c[1]);
        }
        $this->points = $handArr['points'];

        return $this;
    }
}
