<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Desk from Game21.
 */
class DeskTest extends TestCase
{
    /**
     * Construct object and verify it a Desk object.
     */
    public function testCreateObject(): void
    {
        $desk = new Desk();
        $this->assertInstanceOf("\App\Game21\Desk", $desk);

        $res = $desk->toArray();
        $exp = [];
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify method that make fresh desk returns 
     * correct.
     */
    public function testFreshDesk(): void
    {
        $desk = new Desk();
        $res = $desk->freshDesk();
        $this->assertNotEmpty($res);
        $this->assertEquals(52, count($res));
        $nummer = mt_rand(0, 51);
        $this->assertInstanceOf("\App\Game21\CardGraphics", $res[$nummer]);
    }

    /**
     * Construct object and verify of toArray method werks.
     */
    public function testToArray(): void
    {
        $desk = new Desk();
        $cardArray = $desk->freshDesk();
        $res = $desk->toArray();
        $exp = $cardArray[0]->toArray();
        $this->assertNotEmpty($res);
        $this->assertEquals(52, count($res));
        $this->assertEquals($exp, $res[0]);
    }

    /**
     * Construct object and verify if shuffleDesk method works.
     */
    public function testGetDesk(): void
    {
        $desk = new Desk();
        $desk->freshDesk();
        $res = $desk->getDesk();
        $this->assertNotEmpty($res);
        $this->assertEquals(52, count($res));
        $this->assertIsArray($res[0]);
        $exp = "Ess ♠︎";
        $this->assertNotEquals($exp, $res[0]);
    }

    /**
     * Construct object and verify if shuffleDesk method works.
     */
    public function testShuffleDesk(): void
    {
        $desk = new Desk();
        $desk->freshDesk();
        $desk->shuffleDesk();
        $res = $desk->getDesk();
        $this->assertEquals(52, count($res));
        $this->assertIsArray($res[0]);
        $exp = "Ess ♠︎";
        $this->assertNotEquals($exp, $res[0]);
    }

    /**
     * Construct object and verify if set method works.
     */
    public function testSet(): void
    {
        $desk = new Desk();
        $desk->set([[1,2], [3,3]]);
        $res = $desk->getDesk();
        $this->assertEquals(2, count($res));
        $this->assertIsArray($res[0]);
        $exp = "2 ♦︎";
        $this->assertEquals($exp, $res[0][0]);
    }
}