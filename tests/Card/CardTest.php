<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify it it a Card object.
     */
    public function testCreateObject(): void
    {
        $card = new Card();
        $this->assertInstanceOf("\App\Card\Card", $card);

        $res = $card->getCard();
        $exp = [null, null];
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object, give it properties, verify set method
     * returns correct object and if the properties are of the given value.
     */
    public function testSetAndGetCard(): void
    {
        $card = new Card();
        $newCard = $card->set(4, 3);
        $this->assertInstanceOf("\App\Card\Card", $card);
        $this->assertInstanceOf("\App\Card\Card", $newCard);

        $res = $newCard->getCard();
        $exp = [4, 3];
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object, verify if chose method
     * returns correct object and if the properties 
     * are between the given value.
     */
    public function testChoseAndToArray(): void
    {
        $card = new Card();
        $cardArray = $card->chose();
        $this->assertIsArray($cardArray);

        $this->assertLessThanOrEqual(12, $cardArray[0]);
        $this->assertLessThanOrEqual(3, $cardArray[1]);

        $this->assertGreaterThanOrEqual(0, $cardArray[0]);
        $this->assertGreaterThanOrEqual(0, $cardArray[1]);
    }
    

    /**
     * Construct object, give it properties, verify if
     * method getValue() returns correct value
     */
    public function testGetCard(): void
    {
        $card = new Card();
        $card->set(0, 2);
        $res = $card->getCard();
        $this->assertEquals([0, 2], $res);
    }

    /**
     * Construct object, give it properties, verify if
     * method getAsString() returns correct value
     */
    public function testGetAsString(): void
    {
        $card = new Card();
        $card->set(0, 2);
        $res = $card->getAsString();
        $this->assertEquals('0, 2', $res);
    }
}