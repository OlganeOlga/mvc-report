<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from BlackJack.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify it it a correct Card object.
     * @return void
     */
    public function testCreateObject(): void
    {
        $card = new Card();
        $this->assertInstanceOf("\App\BlackJack\Card", $card);

        $result = $card->toArray();
        $expected = [null, null];
        $this->assertEquals($expected, $result);
    }

    /**
     * Construct object, give it properties, verify set method
     * returns correct object and if the properties are of the 
     * given value.
     * @return void
     */
    public function testSetAndToArray(): void
    {
        $card = new Card();
        $newCard = $card->set(4, 3);
        $this->assertInstanceOf("\App\BlackJack\Card", $card);
        $this->assertInstanceOf("\App\BlackJack\Card", $newCard);

        $result = $newCard->toArray();
        $expected = [4, 3];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test function getRandom()
     * 
     * @return void
     * 
     */
    public function testGetRandom(): void
    {
        $card = new Card();
        $card->getRandom();
        $result = $card->toArray();
        $this->assertLessThanOrEqual(11, $result[0]);
        $this->assertGreaterThanOrEqual(0, $result[0]);
        $this->assertLessThanOrEqual(3, $result[1]);
        $this->assertGreaterThanOrEqual(0, $result[1]);
    }

    /**
     * Construct object, verify set method
     * returns correct object and if the properties are of the
     *  given value.
     * @return void
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
}