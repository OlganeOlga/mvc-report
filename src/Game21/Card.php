<?php

namespace App\Game21;

class Card
{
    protected ?int $face;
    protected ?int $suit;
    protected array $card;

    public function __construct()
    {
        $this-> face = null;

        $this->suit = null;

        $this->card = [$this->face, $this->suit];
    }

    /**
    * @param int $fase
    * @param int $sute
    *
    * @return array<int, int|null>
    */
    public function set(int $fase, int $sute): self
    {
        $this->face = $fase; //fase
        $this->sute = $sute; //sute
        return $this;
    }

    /**
     *
     * @return array<int, int>
     */
    public function chose(): array
    {
        $this->face = random_int(0, 12);
        $this->sute = random_int(0, 3);

        return $this->card;
    }

    /**
     *
     * @return array<int, int>
     */
    public function toArray(): array
    {
        return [$this->face, $this->sute];
    }

    /**
     *
     * @return string<$string>
     */
    public function getAsString(): string
    {
        $string = $this->face . ", " . $this->sute;
        return $string;
    }

    /**
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->face + 1;
    }
}
