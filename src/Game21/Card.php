<?php

namespace App\Game21;

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
     * @param int $fase The face value of the card.
     * @param int $sute The sute value of the card.
     * @return self Returns the Card object.
     */
    public function set(int $fase, int $sute): self
    {
        $this->face = $fase; //fase
        $this->sute = $sute; //sute
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

    /**
     * Returns a string representation of the card.
     *
     * @return string A string representation of the card.
     */
    public function getAsString(): string
    {
        $string = $this->face . ", " . $this->sute;
        return $string;
    }

    /**
     * Returns the value of the card.
     *
     * @return int|null The value of the card, or null if the card is not set.
     */
    public function getValue(): ?int
    {
        if($this->face !== null) {
            return $this->face + 1;
        }
        return null;
    }
}
