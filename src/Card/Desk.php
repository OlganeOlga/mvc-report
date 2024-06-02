<?php

namespace App\Card;

use App\Card\CardGraphics;

class Desk
{
    /** 
     * @var CardGraphics[] 
     */
    private array $play = [];

    public function __construct()
    {
        for ($i = 0; $i < 4; $i++) {
            for ($k = 0; $k < 13; $k++) {
                $newCard = new CardGraphics();
                $newCard->set($k, $i);
                $this->play[] = $newCard;
            };
        };
    }

    /**
     * GetDesk method returns array cards
     *
     * @return array<string[]> Array contains strings: card reprresentation and color
     */
    public function getDesk(): array
    {
        $values = [];
        foreach ($this->play as $card) {
            $cardstring = $card->getAsString();
            $cardcolor = $card->getCollor();
            $values[] = [$cardstring, $cardcolor];
        }

        return $values;
    }


    /**
     * GetDeskArray method returns array of strings represented cards of the desk.
     *
     * @return CardGraphics[] Array containing array representation of card elements.
     */
    public function getDeskArray(): array
    {
        return $this->play;
    }

    /**
     * toArray method returns desk as array.
     *
     * @return array<int[0, max], int[]>.Array containing array representation of card elements.
     */
    public function toArray(): array
    {
        $res = [];
        foreach ($this->play as $card) {
            $res[] = $card->getCard();
        }
        return $res;
    }

    /**
     * ShuffleDesk method shuffels desk.
     *
     * @return Desk self with shuffled elements.
     */
    public function shuffleDesk(): self
    {
        shuffle($this->play);
        return $this;
    }

    /**
     * randCard method removes one card from the desk and return it.
     *
     * @return CardGraphics $card self with shuffled elements.
     */
    public function randCard(): CardGraphics
    {
        $cardnum = array_rand($this->play);
        $card = $this->play[$cardnum];
        unset($this->play[$cardnum]);
        return $card;
    }


    /**
     * CountDeskArray method description.
     *
     * @return int amount elements.
     */
    public function countDesk(): int
    {
        $res = count($this->play);
        return $res;
    }

}
