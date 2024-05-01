<?php

namespace App\Card;

class CardGraphics extends Card
{
    /** @var string[] */
    public $facerepresentation = [
        'Ess', '2', '3', '4', '5', '6', '7',
        '8', '9', '10', ' Knekt', 'Dam', 'Kung'
    ];

    /** @var array<string[]> */
    public $suitrepresentation = [
        ['♠︎', 'black'],
        ['♥︎', 'red'],
        ['♦︎', 'red'],
        ['♣︎', 'black'],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAsString(): string
    {
        return $this->facerepresentation[$this->face] . " " .
         $this->suitrepresentation[$this->suit][0];
    }

    public function getCollor(): string
    {
        return $this->suitrepresentation[$this->suit][1];
    }
}
