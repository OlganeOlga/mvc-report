<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from Card.
 */
class DeskTest extends TestCase
{
    /**
     * Construct object and verify it it a CardGraphics object.
     */
    public function testCreateObject(): void
    {
        $desk = new Desk();
        $this->assertInstanceOf("\App\Card\Desk", $desk);

        $res = $desk->getDeskArray();
        
        $this->assertEquals(52, count($res));
        $this->assertInstanceOf("\App\Card\CardGraphics", $res[0]);
    }

    // /**
    //  * Construct CardGraphics object, give it properties, verify set method
    //  * returns correct array and if the properties are of the given value.
    //  */
    // public function testGetAsString(): void
    // {
    //     $card = new CardGraphics();
    //     $card = $card->set(4, 3);


    //     $res = $newCard->getAsString();
    //     $exp = '4 ♣︎';
    //     $this->assertEquals($exp, $res);
    // }

    // /**
    //  * Construct object, give it properties, verify if
    //  * method getCollor() returns correct value
    //  */
    // public function testGetCollor(): void
    // {
    //     $card = new CardGraphics();
    //     $card->set(0, 2);
    //     $res = $card->getCollor();
    //     $this->assertEquals('red', $res);
    // }

}