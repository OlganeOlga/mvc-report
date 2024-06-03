<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from BlackJack.
 */
class PersonTest extends TestCase
{
    /**
     * Construct object and verify it is a Player object.
     * It also check if all properties set as expected
     * It tests getBet() getProfit()
     * 
     * @return void
     */
    public function testCreateObject(): void
    {
        $person = new Person();
        $this->assertInstanceOf("\App\BlackJack\Person", $person);
        
        $this->assertEquals(0, $person->getBet());
        $this->assertEquals(0, $person->getProfit());
        $this->assertEquals('play', $person->getStatus());
        $this->assertEquals(false, $person->blackJack());
        $this->assertEquals('', $person->getName());
    }

    /**
     * test if object Player can use getCard()
     * and getPoints()
     * using mock CardGraphics object
     * 
     * @return void
     */
    public function testGetCard(): void
    {
        $person = new Person;
        // Create a stub for the CardGraphics class.
        $card = $this->createMock(CardGraphics::class);

        // Configure the card.
        $card->method('getValue')->willReturn(2);
        //person get cards
        $person->getCard(clone $card);
        $person->getCard(clone $card);
        $this->assertEquals(4, $person->points());
    }

    /**
     * Test get status
     * 
     * @return void
     */
    public function testGetStatus(): void
    {
        $person = new Person;
        $points = $person->points();
        $this->assertEquals(0, $points);
        $status = $person->getStatus();
       

        $card = $this->createMock(CardGraphics::class);
        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);
        $card3 = $this->createMock(CardGraphics::class);

        // Configure the card.
        $card->method('getValue')->willReturn(7);

        $card1->method('getValue')->willReturn(10);
        $card2->method('getValue')->willReturn(4);
        $card3->method('getValue')->willReturn(2);

        $person->getCard($card);
        $person->getCard($card1);
        $points = $person->points();
        $this->assertEquals(17, $points);

        $person->getCard($card2);
        $points = $person->points();
        $this->assertEquals(21, $points);

        $person->getCard($card3);
        $points = $person->points();
        $this->assertEquals(23, $points);
    }

    /**
     * Test set status
     * 
     * @return void
     */
    public function testSetStatus(): void
    {
        $person = new Person;
        $oldStatus = $person->getStatus();
        //assert status of new Person
        $this->assertEquals('play', $oldStatus);
        $newStatus = "newStatus";
        $person->setStatus($newStatus);
        $status = $person->getStatus();

        //assert status changed
        $this->assertNotEquals($oldStatus, $status);
        $this->assertEquals($newStatus, $status);
    }

    /**
     * Test set and get name
     * 
     * @return void
     */
    public function testSetGetName(): void
    {
        $person = new Person;
        $name = $person->getName();
        $this->assertEquals('', $name);
        $person->setName('Peter');
        $name1 = $person->getName();

        //assert name changed
        $this->assertNotEquals('', $name1);
        $this->assertEquals('Peter', $name1);
    }

    /**
     * tests if object person can use getdoBet()
     *
     * @return void
     */
    public function testDoBetAndGetBet(): void
    {
        $person = new Person;
        $initial = $person->getBet();
        $person->doBet(10);
        $result = $person->getBet();
        $this->assertNotEquals($initial, $result);
        $this->assertEquals(10, $result);
    }

     /**
     * tests if object person can correct get points
     * correct
     * @return void
     */
    public function testPoints(): void
    {
        $card1 = new CardGraphics();
        $card2 = new CardGraphics();
        $card1->set(0,2);
        $card2->set(10,3);
        $person = new Person;
        $person->getCard($card1);
        $person->getCard($card2);
        
        $this->assertEquals(21, $person->points());
    }

    /**
     * tests if object person can correct get points
     * correct
     * @return void
     */
    public function testBlackJack(): void
    {
        $card1 = new CardGraphics();
        $card2 = new CardGraphics();
        $card1->set(0,2);
        $card2->set(10,3);
        $person = new Person;
        $person->getCard($card1);
        $person->getCard($card2);
        
        $this->assertTrue($person->blackJack());
    }
}