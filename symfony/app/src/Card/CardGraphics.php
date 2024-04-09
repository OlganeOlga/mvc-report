<?php
namespace App\Card;

class CardGraphics extends Card
{
    public $facerepresentation = [
        'Ess', '2', '3', '4', '5', '6', '7',
        '8', '9', '10', ' Knekt', 'Dam', 'Kung'
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

    public function getAsString(): string
    {
        return $this->facerepresentation[$this->face] . " " .
         $this->suterepresentation[$this->sute][0];
    }

    public function getCollor(): string
    {
        return $this->suterepresentation[$this->sute][1];
    }
}