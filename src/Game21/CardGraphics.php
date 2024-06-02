<?php

namespace App\Game21;

/**
 * Represents grafic card child class to Card
 */
class CardGraphics extends Card
{
    /**
     *  @var mixed[] $facerepresentation represent faces and faces values
     */
    public array $facerepresentation = [
        [1, 'Ess'],
        [2, '2'],
        [3, '3'],
        [4, '4'],
        [5, '5'],
        [6, '6'],
        [7, '7'],
        [8, '8'],
        [9, '9'],
        [10, '10'],
        [11, 'Knekt'],
        [12, 'Dam'],
        [13, 'Kung']
    ];

    /**
     * @var array<string[]> $suterepresentation represents sutes and their colors
     */
    public array $suterepresentation = [
        ['♠︎', 'black'],
        ['♥︎', 'red'],
        ['♦︎', 'red'],
        ['♣︎', 'black'],
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
        return $this->facerepresentation[$this->face][1] . " " .
        $this->suterepresentation[$this->sute][0];
    }

    /**
    * GetCollor method returns the color of the sute.
    *
    * @return string The color of the sute.
    */
    public function getCollor(): string
    {
        if($this->sute === null) {
            return "";
        }
        return $this->suterepresentation[$this->sute][1];
    }
}
