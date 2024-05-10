<?php

namespace App\Game21;

use App\Game21\CardGraphics;

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
     * @return array<CardGraphics> An array containing CardGraphics objects representing the freshly initialized deck.
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
    * Sets the contents of the deck based on the provided array representation of card values.
    *
    * @param array<int[]> $arr An array of integer arrays representing the values of the cards.
    * @return void
    */
    public function set(
        array $arr = []
    ): void {
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
     * Returns an array representation of all cards in the deck, with their string representations and colors.
     *
     * @return array<string[]> An array of string arrays containing the string representation and color of each card.
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
     * Shuffles all cards in the deck.
     *
     * @return void
     */
    public function shuffleDesk(): void
    {
        shuffle($this->play);
    }

    /**
     * Removes and returns a random card from the deck.
     *
     * @return CardGraphics A CardGraphics object representing the removed card.
     */
    public function takeCard(): CardGraphics
    {
        $randomKey = array_rand($this->play);
        $card = $this->play[$randomKey];
        unset($this->play[$randomKey]);

        return $card;
    }
}
