<?php

namespace App\BlackJack;

/**
 * Class Card represent a playing card in France-English card game
 */
class Card
{
    /**
     * @var int|null Represents the face value of the card.
     */
    protected ?int $face = null;

    /**
     * @var int|null Represents the sute of the card.
     */
    protected ?int $sute = null;

    /**
     * @var int[] Represents the face value of the card.
     */
    protected array $card;

    /**
     * Constructor method
     */
    public function __construct()
    {
        // Initialize the card array with default face and sute values
        $this->card = [$this->face, $this->sute];
    }

    /**
     * Sets the face and sute values of the card.
     *
     * @param int $face The face value of the card.
     * @param int $sute The sute value of the card.
     * @return self Returns the Card object.
     */
    public function set(int $face, int $sute): self
    {
        $this->face = $face; //face
        $this->sute = $sute; //sute
        $this->card = [$this->face, $this->sute]; // Update the card array
        return $this;
    }

    /**
     * Get random face and sute values of the card.
     *
     * @return self Returns the Card object.
     */
    public function getRandom(): self
    {
        $this->face = random_int(0, 11);; //face
        $this->sute = random_int(0, 3);; //sute
        $this->card = [$this->face, $this->sute]; // Update the card array
        return $this;
    }


    /**
     * Randomly chooses a face and sute value for the card.
     *
     * @return array<int> An array containing the chosen face and sute values.
     */
    public function chose(): array
    {
        $this->face = random_int(0, 12);
        $this->sute = random_int(0, 3);

        return $this->card;
    }

    /**
     * Returns the card as an array containing its face and sute values.
     *
     * @return array<int> An array containing the face and sute values of the card.
     */
    public function toArray(): array
    {
        return [$this->face, $this->sute];
    }
}
