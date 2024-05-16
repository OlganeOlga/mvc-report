<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class HandDiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateHandDice(): void
    {
        $hand = new HandDice();
        $this->assertInstanceOf("\App\Dice\HandDice", $hand);

        $res = $hand->getString();
        $this->assertEmpty($res);

        $res = $hand->getValues();
        $this->assertEmpty($res);

        $hand->roll();
        $this->assertEmpty($hand->getValues());
    }

    /**
     * Test if the functions add(Dice $die) and roll() give correct
     * result 
     */
    public function testAddRollandGetNumber(): void
    {
        $hand = new HandDice();

        $die = $this->createMock(GraphicDice::class);
        $die->method('roll')->willReturn(6);
        $die->method('getAsString')->willReturn('6');
        $hand->add($die);
        $hand->roll();
        $res = $hand->getString();
        $this->assertNotEmpty($res);
        $this->assertEquals(['6'], $res);

        $res = $hand->getNumberDices();
        $this->assertNotNull($res);
        $this->assertEquals(1, $res);
    }

    /**
     * Test if the functions getValues() give correct
     * result 
     */
    public function testgetValues(): void
    {
        $hand = new HandDice();

        $die = $this->createMock(GraphicDice::class);
        $die->method('roll')->willReturn(6);
        $die->method('getValue')->willReturn(6);
        $hand->add($die);
        $hand->roll();
        $res = $hand->getValues();
        $this->assertNotNull($res);
        $this->assertEquals([6], $res);

        $res = $hand->getNumberDices();
        $this->assertNotNull($res);
        $this->assertEquals(1, $res);
    }
}
