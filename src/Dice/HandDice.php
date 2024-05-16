<?php

namespace App\Dice;

use App\Dice\Dice;

class HandDice
{
    /**
     *  @var Dice[] array represents all dies in hand
    */
    private array $hand = [];

    /**
     * Constructor method
     */
    public function add(Dice $die): void
    {
        $this->hand[] = $die;
    }

     /**
     * Roll hand.
     *
     * @return int[] array with value of the dice.
     */
    public function roll(): void
    {
        foreach ($this->hand as $die) {
            $die->roll();
        }
    }

    public function getNumberDices(): int
    {
        return count($this->hand);
    }

    /**
     * GetValues method description.
     *
     * @return array<int> Array containing representation of dice values.
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getValue();
        }
        return $values;
    }

    /**
     * GetString method description.
     *
     * @return array<string> Array containing representation of dice as strings.
     */
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getAsString();
        }
        return $values;
    }
}
