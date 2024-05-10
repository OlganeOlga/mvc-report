<?php

namespace App\Game21;

use App\Game21\CardGraphics;

class Hand
{
    /** @var CardGraphics[] */
    protected array $cards;

    protected int $points;

    public function __construct()
    {
        $this->cards = [];

        $this->points = 0;
    }

    /**
     * AddCard method description
     *
     * @param CardGraphics $card
     * @return void.
     */
    public function addCard(CardGraphics $card): void
    {
        $this->cards[] = $card;
        $this->points += $card->getValue();
    }

    /**
     * GetPoints method description
     *
     * @return int points of the hand.
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * toArray method description
     *
     * @return array<string, mixed[]>.
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
     * ToString method description
     *
     * @return array<string[]>.
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
     * ToString method description
     *
     * @return array<string[]>.
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
     * Set method description.
     *
     * @param array<string, mixed> $handArr An array where each element
     * is an integer array representing card values.
     * @return self
     */
    public function set(array $handArr): self
    {
        $this->cards = [];
        foreach ($handArr['cards'] as $c) {
            $card = new CardGraphics();
            $card->set($c[0], $c[1]);
            $this->cards[] = $card;
        }
        $this->points = $handArr['points'];

        return $this;
    }
}
