<?php

namespace App\Game21;

class CardGraphics extends Card
{
    public $facerepresentation = [
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

    public $suterepresentation = [
        ['♠︎', 'black'],
        ['♥︎', 'red'],
        ['♦︎', 'red'],
        ['♣︎', 'black'],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function toString(): string
    {
        return $this->facerepresentation[$this->face][1] . " " .
        $this->suterepresentation[$this->sute][0];
    }

    public function getCollor(): string
    {
        return $this->suterepresentation[$this->sute][1];
    }
}
