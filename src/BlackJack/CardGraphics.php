<?php

namespace App\BlackJack;

/**
 * Represents grafic card child class to Card
 */
class CardGraphics extends Card
{
    /**
     *  @var mixed[] $facerepresentation represent fases and fases values
     */
    public array $facerepresentation = [
        [0, 'ace'],
        [2, '2'],
        [3, '3'],
        [4, '4'],
        [5, '5'],
        [6, '6'],
        [7, '7'],
        [8, '8'],
        [9, '9'],
        [10, '10'],
        [10, 'jack'],
        [10, 'queen'],
        [10, 'king']
    ];

    /**
     * @var string[] $suterepresentation represents sutes and their colors
     */
    public array $suterepresentation = [
        'spades',
        'hearts',
        'diamonds',
        'clubs',
    ];

    /**
     * Constructor method that calls the parent constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ToString method returns a string representation of the card.
     *
     * @return string A string representation of the card.
     */
    public function toString(): string
    {
        // if($this->face == null) {
        //     return "";
        // }
        return $this->facerepresentation[$this->face][1] . "_of_" .
        $this->suterepresentation[$this->sute];
    }

    /**
    * GCreate url for the card image.
    *
    * @return string The url.
    */
    public function getUrl(): string
    {
        if($this->sute === null || $this->face === null) {
            return "";
        }
        $cardUrl = "img/img/cards/English_pattern_" . $this->toString() . ".svg";
        return $cardUrl;
    }

    /**
     * Returns value of the card in the BlackJack
     * 
     * @return int value of the card
     */
    public function getValue(): int
    {
        return $this->facerepresentation[$this->face][0];
    }

    /**
     * Returns sute of the card in the BlackJack
     * 
     * @return string sute of the card
     */
    public function getFace(): string
    {
        return $this->facerepresentation[$this->face][1];
    }
}
