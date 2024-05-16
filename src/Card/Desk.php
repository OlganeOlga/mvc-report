<?php

namespace App\Card;

use App\Card\CardGraphics;

class Desk
{
    /** @var int[] */
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
     * GetDesk method returns array of string arrays representing all cards in desk
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

    // /**
    //  * setDesk method description
    //  *
    //  * @param array<int[]> $array of integers arrays
    //  * @return Desk Array contains strings: card reprresentation and color
    //  */
    // public function setDesk($array): Desk
    // {

    //     foreach ($this->play as $card) {
    //         $cardstring = $card->getAsString();
    //         $cardcolor = $card->getCollor();
    //         $values[] = [$cardstring, $cardcolor];
    //     }

    //     return $values;
    // }

    /**
     * GetDeskArray method description.
     *
     * @return array<int> Array containing array representation of card elements.
     */
    public function getDeskArray(): array
    {
        return $this->play;
    }

    /**
     * toArray method returns desk as array.
     *
     * @return array<int> Array containing array representation of card elements.
     */
    public function toArray(): array
    {
        $res = [];
        foreach ($this->play as $card) {
            $res[] = $card.toArray();
        }
        return $this->play;
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
     * @return Desk self with shuffled elements.
     */
    public function countDesk(): self
    {
        count($this->play);
        return $this;
    }

}
