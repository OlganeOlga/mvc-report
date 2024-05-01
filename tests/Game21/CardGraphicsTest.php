<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from Game21.
 */
class CardGraphicsTest extends TestCase
{
    /**
     * Construct object and verify it it a CardGraphics object.
     */
    public function testCreateObject(): void
    {
        $card = new CardGraphics();
        $this->assertInstanceOf("\App\Game21\CardGraphics", $card);

        $res = $card->toArray();
        $exp = [null, null];
        $this->assertEquals($exp, $res);
    }

    /**
     * Verify it it a CardGraphics object works properly with methid
     * overriden method toString.
     */
    public function testOverridenToString(): void
    {
        $card = new CardGraphics();
        $res = $card->toString();
        $this->assertIsString($res);

        $card->set(1,1);
        $res = $card->toString();
        $exp = "2 ♥︎";
        $this->assertEquals($exp, $res);
    }

    /**
     * Verify it it a CardGraphics object works properly with methid
     * overriden method toString.
     */
    public function testGetCollor(): void
    {
        $card = new CardGraphics();
        $res = $card->getCollor();
        $this->assertIsString($res);

        $card->set(1,1);
        $res = $card->getCollor();
        $exp = "red";
        $this->assertEquals($exp, $res);
    }
}