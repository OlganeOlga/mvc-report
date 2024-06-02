<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from BlackJack.
 */
class CardGraphicsTest extends TestCase
{
    /**
     * Construct object and verify it it a CardGraphics object.
     * @return void
     */
    public function testCreateObject(): void
    {
        $card = new CardGraphics();
        $this->assertInstanceOf("\App\BlackJack\CardGraphics", $card);

        $res = $card->toArray();
        $exp = [null, null];
        $this->assertEquals($exp, $res);
    }

    /**
     * Verify it it a CardGraphics object works properly with methid
     * overriden method toString.
     * @return void
     */
    public function testToString(): void
    {
        $card = new CardGraphics();       

        $card->set(1,1);
        $res = $card->toString();
        $exp = "2_of_hearts";
        $this->assertIsString($res);
        $this->assertEquals($exp, $res);
    }

    /**
     * Verify it it a CardGraphics object return proper sute
     * @return void
     */
    public function testGetUrl(): void
    {
        $card = new CardGraphics();
        
        $res = $card->getUrl();
        $this->assertEquals("", $res);

        $card->set(1,1);
        $res = $card->getUrl();
        $expected = "img/img/cards/English_pattern_2_of_hearts.svg";
        $this->assertEquals($expected, $res);
    }

    /**
     * Verify it it a CardGraphics object return proper sute
     * @return void
     */
    public function testGetValue(): void
    {
        $card = new CardGraphics();
        $card->set(10,1);
        $res = $card->getValue();
        $this->assertIsInt($res);
        $exp = 10;
        $this->assertEquals($exp, $res);
    }

     /**
     * Verify it it a CardGraphics object return proper face
     * @return void
     */
    public function testGetFace(): void
    {
        $card = new CardGraphics();
        $card->set(1,1);
        $res = $card->getFace();
        $this->assertIsString($res);
        $exp = "2";
        $this->assertEquals($exp, $res);
    }
}