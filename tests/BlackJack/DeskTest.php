<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Desk from BlackJack.
 */
class DeskTest extends TestCase
{
    /**
     * Construct object and verify it a Desk object.
     * 
     * @return void
     */
    public function testCreateObject(): void
    {
        $desk = new Desk();
        $this->assertInstanceOf("\App\BlackJack\Desk", $desk);

        $res = $desk->toArray();
        $exp = [];
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify method that make fresh desk returns 
     * correct.
     * 
     * @return void
     */
    public function testFreshDesk(): void
    {
        $desk = new Desk();
        $fresh = $desk->freshDesk();
        $this->assertInstanceOf("\App\BlackJack\Desk", $fresh);
        $result= $fresh->toArray();
        $this->assertNotEmpty($result);
        $this->assertEquals(52, count($result));
    }

    /**
     * Construct object and verify of toArray method werks.
     * 
     * @return void
     */
    public function testToArray(): void
    {
        $desk = new Desk();
        $desk->freshDesk();
        $res = $desk->toArray();
        $exp = [0, 0];
        $this->assertNotEmpty($res);
        $this->assertEquals(52, count($res));
        $this->assertEquals($exp, $res[0]);
    }

    /**
     * Construct object and verify if shuffleDesk method works.
     * 
     * @return void
     */
    public function testGetDesk(): void
    {
        $desk = new Desk();
        $desk->freshDesk();
        $result = $desk->getDesk();
        $this->assertNotEmpty($result);
        $this->assertEquals(52, count($result));
        $this->assertIsString($result[0]);
        $expected = "img/img/cards/English_pattern_ace_of_spades.svg";
        $this->assertEquals($expected, $result[0]);
    }

    /**
     * Construct object and verify if shuffleDesk method works.
     * 
     * @return void
     */
    public function testShuffleDesk(): void
    {
        $desk = new Desk();
        $desk->freshDesk();
        $originalDesk = $desk->toArray();

        $desk->shuffleDesk();
        $shuffledDesk = $desk->toArray();

        $this->assertNotEmpty($shuffledDesk);
        $this->assertEquals(52, count($shuffledDesk));
        $this->assertNotEquals($originalDesk, $shuffledDesk);
    }

    // /**
    //  * Construct object and verify if set method works.
    //  * 
    //  * @return void
    //  */
    // public function testSet(): void
    // {
    //     $desk = new Desk();
    //     $desk->set([[1,2], [3,3]]);
    //     $res = $desk->getDesk();
    //     $this->assertEquals(2, count($res));
    //     $this->assertIsString($res[0]);
    //     $exp = "img/img/cards/English_pattern_2_of_diamonds.svg";
    //     $this->assertEquals($exp, $res[0]);
    // }

    /**
     * Construct object and verify if TakeCard method works.
     * 
     * @return void
     */
    public function testTakeCard(): void
    {
        $desk = new Desk();
        $desk->freshDesk();
        $resultat = $desk->takeCard();
        $this->assertInstanceOf("\App\BlackJack\CardGraphics", $resultat);
        $cardurl = $resultat->getUrl();
        $this->assertIsString($cardurl);
        $expected = "img/img/cards/English_pattern_ace_of_spades.svg";
        $this->assertEquals($expected, $cardurl);
        $this->assertEquals(51, count($desk->getDesk()));
    }
}