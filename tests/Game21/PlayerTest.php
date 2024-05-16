<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from Game21.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object and verify it is a Player object.
     * It also check if all properties set as expected
     * It tests getBet(), getProfit() and getHand()
     */
    public function testCreateObject(): void
    {
        $player = new Player();
        $this->assertInstanceOf("\App\Game21\Player", $player);

        $hand = $player->getHand();
        //$exp = ['points' => 0, 'cards' => []];
        $this->assertEquals(0, $player->getBet());
        $this->assertEquals(0, $player->getProfit());
        $this->assertEquals([], $hand);
    }

    /**
     * test if object Player can use getCard()
     * and getPoints()
     * using mock CardGraphics object
     */
    public function testGetCard(): void
    {
        $player = new Player;
        // Create a stub for the CardGraphics class.
        $stub = $this->createMock(CardGraphics::class);

        // Configure the stub.
        $stub->method('getValue')->willReturn(2);
        //player get cards
        $player->getCard(clone $stub);
        $player->getCard(clone $stub);
        $this->assertEquals(4, $player->points());
    }

    /**
     * tests if object Player can use getdoBet()
     *
     */
    public function testDoBet(): void
    {
        $player = new Player;
        $player->doBet(10);
        $res = $player->getBet();
        $this->assertEquals(10, $res);
    }

    /**
     * tests if object Player can use toArray()
     * correct
     */
    public function testToArray(): void
    {
        $player = new Player;
        $player->doBet(10);
        $exp = [
            'hand' => [
                'points' => 0,
                'cards' => []
            ],
            'bet' => 10,
            'profit' => 0
        ];
        
        $this->assertEquals($exp, $player->toArray());
    }
}