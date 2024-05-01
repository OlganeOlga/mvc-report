<?php

namespace App\Game21;

/**
 * Class Card represent a card frome France-English cardplay
 */
class Card
{
    protected ?int $face = null;
    protected ?int $sute = null;

    /** @var int[] */
    protected array $card;

    public function __construct()
    {
        // $this-> face = null;

        // $this->sute = null;

        $this->card = [$this->face, $this->sute];
    }

    /**
     * GetString method description.
     *
     * @return Card object of type Card
     * given card with integers.
     */
    public function set(int $fase, int $sute): self
    {
        $this->face = $fase; //fase
        $this->sute = $sute; //sute
        return $this;
    }

    /**
     * Chose method description.
     *
     * @return array<int> Array containing representation of
     * random card with integers.
     */
    public function chose(): array
    {
        $this->face = random_int(0, 12);
        $this->sute = random_int(0, 3);

        return $this->card;
    }

    /**
     * GetString method description.
     *
     * @return array<int> Array containing representation of the card with integers.
     */
    public function toArray(): array
    {
        return [$this->face, $this->sute];
    }

    /**
     * GetAsString method description.
     *
     * @return string representation of card as string.
     */
    public function getAsString(): string
    {
        $string = $this->face . ", " . $this->sute;
        return $string;
    }

    /**
     * GetValue method description.
     *
     * @return int|null value of the cars or null if card is not set
     */
    public function getValue(): ?int
    {
        if($this->face != null){
            return $this->face + 1;
        }
        return null;
    }
}
