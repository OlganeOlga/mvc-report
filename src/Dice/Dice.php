<?php

namespace App\Dice;

class Dice
{
    /**
     * @var int|null Represents the value of the dice.
     */
    protected ?int $value;

    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->value = null;
    }

    /**
     * Roll dice.
     *
     * @return int value of the dice.
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    /**
     * Function shows value of the dice.
     *
     * @return int|null value of the dice.
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * Shows value of the dice as string.
     *
     * @return string value of the dice as string.
     */
    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
