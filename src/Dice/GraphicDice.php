<?php

namespace App\Dice;

class GraphicDice extends Dice
{
    /** 
     * @var string[] Represents the value of the dice. 
    */
    private array $representation = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Override getAsString for parrent class
     *  value of the dice as string.
     *
     * @return string valuerepresentation of the GraphicDice.
     */
    public function getAsString(): string
    {
        return $this->representation[$this->value - 1];
    }
}
