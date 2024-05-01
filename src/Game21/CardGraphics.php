<?php

namespace App\Game21;

class CardGraphics extends Card
{
    /** @var mixed[] */
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

    /** @var array<string[]> */
    public array $suterepresentation = [
        ['♠︎', 'black'],
        ['♥︎', 'red'],
        ['♦︎', 'red'],
        ['♣︎', 'black'],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ToString method description.
     *
     * @return string string representation of the card
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
     * GetCollor method description.
     *
     * @return string string representation of the collor
     */
    public function getCollor(): string
    {
        if($this->sute == null) {
            return "";
        }
        return $this->suterepresentation[$this->sute][1];
    }
}
