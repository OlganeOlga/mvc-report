<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Hand from Game21.
 */
class HandTest extends TestCase
{
    /**
     * Construct object and verify it it a Hand object.
     * 
     * @return void
     */
    public function testCreateObject(): void
    {
        $hand = new Hand();
        $this->assertInstanceOf("\App\Game21\Hand", $hand);

        $res = $hand->toArray();
        $exp = ['points' => 0, 'cards' => []];
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct object and verify it get correct points.
     * 
     * @return void
     */
    public function testGetPoints(): void
    {
        $hand = new Hand();
        $card = $this->createMock(CardGraphics::class);
        $card->method('chose')
            ->willReturn([2, 4]);
        $card->chose();
        $hand->addCard($card);
        $hand->addCard($card);
        $this->assertEquals($card->getValue() * 2, $hand->getPoints());
    }

    /**
     * Construct object and verify of toString method werks.
     * 
     * @return void
     */
    public function testToString(): void
    {
        $hand = new Hand();
        $card = $this->createMock(CardGraphics::class);
        $card->method('chose')
            ->willReturn([2, 4]);
        $card->chose();
        $hand->addCard($card);
        $hand->addCard($card);
        $res = $hand->toString();
        $exp = $card->toString();
        $this->assertNotEmpty($res);
        $this->assertEquals(2, count($res));
        $this->assertEquals($exp, $res[0][0]);
    }

    /**
     * Construct object and verify of toArray method werks.
     * 
     * @return void
     */
    public function testToArray(): void
    {
        $hand = new Hand();
        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);
        // Configure the stub.
        $card1->method('chose')->wilLReturn([2, 3]);
        $card1->method('toArray')
            ->willReturn([2, 3]);
        $card1->method('chose')->wilLReturn([10, 2]);   
        $card2->method('toArray')
            ->willReturn([10, 2]);
        $hand->addCard($card1);
        $hand->addCard($card2);
        $res = $hand->toArray();
        $exp = $card1->toArray();
        $this->assertNotEmpty($res);
        $this->assertEquals(2, count($res));
        $this->assertEquals($exp, $res['cards'][0]);
    }

    /**
     * Construct object and verify 
     * method getHand with help of mocked CardGraphics objekt.
     * 
     * @return void
     */
    public function testGetHand(): void
    {
        $hand = new Hand();
        // Create a stub for the CardGraphics class.
        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);
        // Configure the stub.
        $card1->method('toString')
            ->willReturn("2 ♣︎");
        $card1->method('getCollor')
            ->willReturn("black");
            
        $card2->method('toString')
            ->willReturn("Knekt ♦︎");
        $card2->method('getCollor')
            ->willReturn("red");
            
        $hand->addCard($card1);
        $hand->addCard($card2);
        $res = $hand->getHand();
        $exp = [["2 ♣︎", "black"], ["Knekt ♦︎", "red"]];
        $this->assertNotEmpty($res);
        $this->assertEquals(2, count($res));
        $this->assertEquals($exp, $res);
    }

    /**
     * Tests method set()
     * 
     * @return void 
     */
    public function testSetMethod(): void
    {
        $hand = new Hand();

        // input
        $inputArray = [
            'cards' => [
                [1, 3],
                [10, 2],
            ],
            'points' => 15,
        ];

        $result = $hand->set($inputArray);

        // Assertions
        $this->assertInstanceOf(Hand::class, $result);
        $this->assertCount(2,  $hand->toString());
        $expToString = [['2 ♣︎', 'black'], ['Knekt ♦︎', 'red']];
        // Check each card in cards property
        $this->assertIsArray($hand->toString());
        $this->assertEquals($expToString, $hand->toString());

        // Check points property
        $this->assertEquals(15, $hand->getPoints());
    }
}
