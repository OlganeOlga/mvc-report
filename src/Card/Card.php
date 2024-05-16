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
     * @return Card Card object with given properties.
     */
    public function set(int $aFase, int $aSuit): Card
    {
        $this->face = $aFase;
        $this->suit = $aSuit;

        return $this;
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
     * GetCard method card as array.
     *
     * @return int[] Array containing the chosencharacteristic of the card.
     */
    public function getCard(): array
    {
        return [$this->face, $this->suit];
    }

    /**
     * GetAsString method returns cardobject as string.
     *
     * @return string string representation of the card.
     */
    public function getAsString(): string
    {
        $string = $this->face . ", " . $this->suit;
        return $string;
    }

}
