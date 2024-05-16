<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from Card.
 */
class CardGraphicsTest extends TestCase
{
    /**
     * Construct object and verify it it a CardGraphics object.
     */
    public function testCreateObject(): void
    {
        $card = new CardGraphics();
        $this->assertInstanceOf("\App\Card\CardGraphics", $card);

        $res = $card->getCard();
        $exp = [null, null];
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct CardGraphics object, give it properties, verify set method
     * returns correct array and if the properties are of the given value.
     */
    public function testGetAsString(): void
    {
        $card = new CardGraphics();
        $card = $card->set(4, 3);


        $res = $card->getAsString();
        $exp = '5 ♣︎';
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object, give it properties, verify if
     * method getCollor() returns correct value
     */
    public function testGetCollor(): void
    {
        $card = new CardGraphics();
        $card->set(0, 2);
        $res = $card->getCollor();
        $this->assertEquals('red', $res);
    }

}