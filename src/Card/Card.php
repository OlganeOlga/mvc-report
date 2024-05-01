<?php

namespace App\Card;

/**
 * Class represents a card from Fance-English
 * card-play
 */
class Card
{
    protected ?int $face = null;
    protected ?int $suit = null;
    /** @var int[] */
    protected array $card;

    /**
     * Constructor
     */
    public function __construct()
    {
        // $this-> face = null;

        // $this->suit = null;

        $this->card = [$this->face, $this->suit];
    }

    /**
     * Set method description.
     *
     * @return mixed[] Array containing the given int elements.
     */
    public function set(int $aFase, int $aSuit): array
    {
        $this->face = $aFase;
        $this->suit = $aSuit;

        return $this->card;
    }

    /**
     * Choose method description.
     *
     * @return mixed[] Array containing the chosen elements.
     */
    public function chose(): array
    {
        $this->face = random_int(0, 12);
        $this->suit = random_int(0, 3);


        return $this->card;
    }

    /**
     * GetCard method description.
     *
     * @return mixed[] Array containing the chosencharacteristic of the card.
     */
    public function getCard(): array
    {
        return [$this->face, $this->suit];
    }

    /**
     * Choose method description.
     *
     * @return string string representation of the card.
     */
    public function getAsString(): string
    {
        $string = $this->face . ", " . $this->suit;
        return $string;
    }
}
