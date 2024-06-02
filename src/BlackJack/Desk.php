<?php

namespace App\BlackJack;

use App\BlackJack\CardGraphics;

/**
 * Represents the deck of cards used in the game.
 *
 * This class manages a deck of cards, which can be freshly initialized, shuffled,
 * and manipulated. It utilizes instances of the CardGraphics class to represent each card.
 * The deck can be represented as an array of CardGraphics objects.
 */
class Desk
{
    /**
     * @var array<CardGraphics> An array containing CardGraphics objects representing the deck of cards.
    */
    protected array $play;

    /**
     * Constructor method that initializes an empty deck.
     */
    public function __construct()
    {
        $this->play = [];
    }

    /**
     * Initializes a fresh deck of 52 cards, with each card represented by a CardGraphics object.
     *
     * @return Desk
     */
    public function freshDesk(): Desk
    {
        for ($i = 0; $i < 4; $i++) {
            for ($k = 0; $k < 13; $k++) {
                $newCard = new CardGraphics();
                $newCard->set($k, $i); //set fase, sute
                $this->play[] = $newCard;
            };
        };
        return $this;
    }

    /**
     * Returns an array representation of all cards in the deck.
     *
     * @return array<int[]> An array of integer arrays representing the values of all cards in the deck.
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
     * Returns url for pictures of all cards in desk.
     *
     * @return string[] An array of string arrays containing url to img of each card
     */
    public function getDesk(): array
    {
        $corts = [];
        foreach ($this->play as $cort) {
            $cortUrl = $cort->getUrl();
            $corts[] = $cortUrl;
        }
        return $corts;
    }

    /**
     * Shuffles all cards in the deck.
     *
     * @return Desk
     */
    public function shuffleDesk(): Desk
    {
        shuffle($this->play);
        return $this;
    }

    /**
     * Removes and returns first card in the deck.
     *
     * @return CardGraphics A CardGraphics object representing the removed card.
     */
    public function takeCard(): CardGraphics
    {
        $card = array_shift($this->play);

        return $card;
    }
}
