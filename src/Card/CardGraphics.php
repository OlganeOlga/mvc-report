<?php

namespace App\Card;

class CardGraphics extends Card
{
    /**
     * @var array<int, string>
     */
    public array $facerepresentation = [
        'Ess', '2', '3', '4', '5', '6', '7',
        '8', '9', '10', ' Knekt', 'Dam', 'Kung'
    ];

    /**
     * @var array<array<int, string>>
     */
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
    *
    * @return string
    */
    public function getAsString(): string
    {
        return $this->facerepresentation[$this->face] . " " .
         $this->suterepresentation[$this->sute][0];
    }

    /**
    *
    * @return string
    */
    public function getCollor(): string
    {
        return $this->suterepresentation[$this->sute][1];
    }
}
