<?php

namespace App\Card;

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

    public function set($a_fase, $a_sute)
    {
        $this->face = $a_fase;
        $this->sute = $a_sute;

        return $this->card;
    }

    public function chose(): array
    {
        $this->face = random_int(0, 12);
        $this->sute = random_int(0, 3);


        return $this->card;
    }

    public function getCard(): array
    {
        return [$this->face, $this->sute];
    }

    public function getAsString(): string
    {
        $string = $this->face . ", " . $this->sute;
        return $string;
    }
}
