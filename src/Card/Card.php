<?php

namespace App\Card;

class Card
{
    protected ?int $face;
    protected ?int $sute;

    /**
     * @var array<int, int|null>
     */
    protected array $card;

    public function __construct()
    {
        $this-> face = null;

        $this->sute = null;

        $this->card = [$this->face, $this->sute];
    }

    /**
    * @param int $aFase
    * @param int $aSute
    *
    * @return array<int, int|null>
    */
    public function set(int $aFase, int $aSute): array
    {
        $this->face = $aFase;
        $this->sute = $aSute;

        return $this->card;
    }

    /**
     *
     * @return array<int, int|null>
     */
    public function chose(): array
    {
        $this->face = random_int(0, 12);
        $this->sute = random_int(0, 3);

        return $this->card;
    }

    /**
     *
     * @return array<int, int|null>
     */
    public function getCard(): array
    {
        return [$this->face, $this->sute];
    }

    /**
     *
     * @return string
     */
    public function getAsString(): string
    {
        $string = $this->face . ", " . $this->sute;
        return $string;
    }

    /**
     *
     * @return array<int, int|null>
     */
    public function toArray(): array
    {
        return [$this->face, $this->sute];
    }
}
