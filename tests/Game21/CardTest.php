<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from Game21.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify it it a Card object.
     */
    public function testCreateObject(): void
    {
        $card = new Card();
        $this->assertInstanceOf("\App\Game21\Card", $card);

        $res = $card->toArray();
        $exp = [null, null];
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object, give it properties, verify set method
     * returns correct object and if the properties are of the given value.
     */
    public function testSetAndToArray(): void
    {
        $card = new Card();
        $newCard = $card->set(4, 3);
        $this->assertInstanceOf("\App\Game21\Card", $card);
        $this->assertInstanceOf("\App\Game21\Card", $newCard);

        $res = $newCard->toArray();
        $exp = [4, 3];
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object, verify set method
     * returns correct object and if the properties are of the given value.
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
    public function testGetValue(): void
    {
        $card = new Card();
        $card->set(0, 2);
        $res = $card->toArray();
        $this->assertEquals([0, 2], $res);
        $this->assertEquals(1, $card->getValue());
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