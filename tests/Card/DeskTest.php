<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Desk from Card.
 */
class DeskTest extends TestCase
{
    /**
     * Construct object and verify it it a Desk object.
     */
    public function testCreateObject(): void
    {
        $desk = new Desk();
        $this->assertInstanceOf("\App\Card\Desk", $desk);

        $res = $desk->getDeskArray();
        
        $this->assertEquals(52, count($res));
        $this->assertInstanceOf("\App\Card\CardGraphics", $res[0]);
    }

    /**
     * Construct Desk object, give it properties, verify set method
     * returns correct array and if the properties are of the given value.
     */
    public function testGetDesk(): void
    {
        $desk = new Desk();
        $res = $desk->getDesk();

        $exp = ['Ess ♠︎', 'black'];
        $this->assertEquals($exp, $res[0]);
    }

    /**
     * Construct object, give it properties, verify if
     * method getToArray() returns correct value
     */
    public function testToArray(): void
    {
        $desk = new Desk();
        $res = $desk->toArray();

        $this->assertEquals([0,0], $res[0]);
    }

    /**
     * Construct object, give it properties, verify if
     * method shuffleDesk() returns correct value
     */
    public function testShuffleDesk(): void
    {
        $desk = new Desk();
        $res = $desk->shuffleDesk();

        $this->assertInstanceOf("\App\Card\Desk", $res);
        $res1 = $res->toArray();
        $this->assertContains([0,0], $res1);
    }

    /**
     * Construct object, give it properties, verify if
     * method randCard() returns correct value
     */
    public function testRandCard(): void
    {
        $desk = new Desk();
        $res = $desk->randCard();

        $this->assertInstanceOf("\App\Card\CardGraphics", $res);
    }

    /**
     * Construct object, give it properties, verify if
     * method countDesk() returns correct value
     */
    public function testCountDesk(): void
    {
        $desk = new Desk();
        $res = $desk->countDesk();

        $this->assertEquals(52, $res);
    }
}