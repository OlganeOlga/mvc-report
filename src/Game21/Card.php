<?php

namespace App\Game21;

class Card
{
    protected $face;
    protected $suit;
    protected $card;

    public function __construct()
    {
        $this-> face = null;

        $this->suit = null;

        $this->card = [$this->face, $this->suit];
    }

    public function set(int $fase, int $sute): self
    {
        $this->face = $fase; //fase
        $this->sute = $sute; //sute
        return $this;
    }

    public function chose(): array
    {
        $this->face = random_int(0, 12);
        $this->sute = random_int(0, 3);


        return $this->card;
    }

    public function toArray(): array
    {
        return [$this->face, $this->sute];
    }

    public function getAsString(): string
    {
        $string = $this->face . ", " . $this->sute;
        return $string;
    }

    public function getValue(): int
    {
        return $this->face + 1;
    }

}