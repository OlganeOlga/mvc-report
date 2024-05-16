<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class GraphicDiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice(): void
    {
        $die = new GraphicDice();
        $this->assertInstanceOf("\App\Dice\GraphicDice", $die);
    }

    /**
     * Construct GraphicDice and verify 
     * method GetAsString on GraphicsDice returns correct string
     * properties, use no arguments.
     */
    public function testGetAsStringReturnsCorrectRepresentation(): void
    {
        // Arrange
        $dice = new GraphicDice();

        // Act
        $dice->roll();
        $result = $dice->getAsString();

        // Assert
        $this->assertMatchesRegularExpression('/⚀|⚁|⚂|⚃|⚄|⚅/', $result);
    }

}
