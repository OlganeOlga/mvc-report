<?php

namespace App\BlackJack;

use App\BlackJack\CardGraphics;

class Hand
{
    /** @var CardGraphics[] */
    protected array $cards;

    /**
     * @var int show points of the hand
     */
    protected int $points;

    /**
     * @var bool show if hand is soft
     */
    protected bool $soft;

    public function __construct()
    {
        $this->cards = [];

        $this->points = 0;

        $this->soft = false;
    }

    /**
     * AddCard method add one card to the hand
     *
     * @param CardGraphics $card
     * @return void
     */
    public function addCard(CardGraphics $card): void
    {
        $this->cards[] = $card;
        if($card->getFace() === 'ace') {
            $this->soft = true;
        }
        $this->points += $card->getValue();
    }

    /**
     * GetPoints returns curren points of the hand
     *
     * @return int points of the hand.
     */
    public function getPoints(): int
    {
        
        if(!$this->soft)
        {
            return $this->points;
        }

        if($this->points > 10) {
            return $this->points + 1;
        }
        return $this->points + 11;
    }

    /**
     * toArray returns array of the hand
     *
     * @return array{points: int, cards: array<int<0, max>, array<int>>, soft: bool}
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
            'soft' => $this->soft,
        ];
        return $array;
    }

    /**
     * getCardFaces method returns array of cards faces
     * @return string[].
     */
    public function getCardFaces(): array
    {
        $cardArr = [];
        if (!empty($this->cards)) {
            foreach ($this->cards as $card) {
                $cardArr[] = $card->getFace();
            }
        }
        return $cardArr;
    }

    /**
     * getHand method returns array of cards url
     * @return string[].
     */
    public function getHand(): array
    {
        $cardArr = [];
        if (!empty($this->cards)) {
            foreach ($this->cards as $card) {
                $cardArr[] = $card->getUrl();
            }
        }
        return $cardArr;
    }

    /**
     * Show if hand is soft
     * 
     * @return bool
     */
    public function soft() {
        return $this->soft;
    }

    /**
     * Shows if hand is a Black Jack
     * 
     * @return bool
     */
    public function blackJack(): bool
    {
        if(count($this->cards) === 2 && $this->soft === true && $this->getPoints() === 21)
        {
            return true;
        }
        return false;
    }

    /**
     * split hand
     * 
     * @return ?CardGraphics one of the cards
     */
    public function split(): ?CardGraphics
    {
        if(count($this->cards) === 2 && $this->cards[0]->getFace() === $this->cards[1]->getFace()) {
            $card = array_shift($this->cards);
            $points = $card->getValue();
            $this->points = $points;
        
            return $card;
        }
        return null;
    }

    /**
     * is hand splitable
     * 
     * @return bool
     */
    public function canSplit(): bool
    {
        $cards = $this->cards;
        if(count($cards) === 2 && $cards[0]->getFace() === $cards[1]->getFace()) {
            return true;
        }
        return false;
    }
}
