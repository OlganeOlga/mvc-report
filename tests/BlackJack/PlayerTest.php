<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from BlackJack.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object and verify it is a Player object.
     * It also check if all properties set as expected
     * It tests getBet(), getProfit() and getHand()
     * 
     * @return void
     */
    public function testCreateObject(): void
    {
        $player = new Player();
        $this->assertInstanceOf("\App\BlackJack\Player", $player);

        $expected = [
            'bet' => 0,
            'hand' => [],
            'points' => 0,
            'soft' => 0,
            'status' => 'play',
            'insure' => false,
            'blackJack' => false,
            'split' => false,
            'profit' => 0
        ];
        $hand = $player->getHand();
        
        $this->assertEquals(0, $player->getBet());
        $this->assertEquals(0, $player->getProfit());
        $this->assertEquals('play', $player->getStatus());
        $this->assertEquals(false, $player->insurance());
        $this->assertEquals(false, $player->blackJack());
        $this->assertEquals(false, $player->canSplit());
        $this->assertEquals($expected, $hand);
        $this->assertEquals('', $player->getName());
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
        $player = new Player;
        // Create a stub for the CardGraphics class.
        $card = $this->createMock(CardGraphics::class);

        // Configure the card.
        $card->method('getValue')->willReturn(2);
        //player get cards
        $player->getCard(clone $card);
        $player->getCard(clone $card);
        $this->assertEquals(4, $player->points());
    }

    /**
     * Test get status
     * 
     * @return void
     */
    public function testGetStatus(): void
    {
        $player = new Player;
        $points = $player->points();
        $this->assertEquals(0, $points);
        $status = $player->getStatus();
        //assert status of new player
        $this->assertEquals('play', $status);

        $card = $this->createMock(CardGraphics::class);
        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);
        $card3 = $this->createMock(CardGraphics::class);

        // Configure the card.
        $card->method('getValue')->willReturn(7);

        $card1->method('getValue')->willReturn(10);
        $card2->method('getValue')->willReturn(4);
        $card3->method('getValue')->willReturn(2);

        $player->getCard($card);
        $player->getCard($card1);
        $status = $player->getStatus();
        $this->assertEquals('play', $status);

        $player->getCard($card2);
        $status = $player->getStatus();

        $status = $player->getStatus();
        //$this->assertEquals('wait', $status);

        $player->getCard($card3);
        $status = $player->getStatus();
        //$this->assertEquals('fat', $status);
    }

    /**
     * Test set status
     * 
     * @return void
     */
    public function testSetStatus(): void
    {
        $player = new Player;
        $oldStatus = $player->getStatus();
        $newStatus = "newStatus";
        $player->setStatus($newStatus);
        $status = $player->getStatus();

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
        $player = new Player;
        $player->setName('Peter');
        $name = $player->getName();

        //assert name changed
        $this->assertNotEquals('', $name);
        $this->assertEquals('Peter', $name);
    }

    /**
     * tests if object Player can use getdoBet()
     *
     * @return void
     */
    public function testDoBetAndGetBet(): void
    {
        $player = new Player;
        $initial = $player->getBet();
        $player->doBet(10);
        $result = $player->getBet();
        $this->assertNotEquals($initial, $result);
        $this->assertEquals(10, $result);
    }

     /**
     * tests if object Player can correct get points
     * correct
     * @return void
     */
    public function testPoints(): void
    {
        $card1 = new CardGraphics();
        $card2 = new CardGraphics();
        $card1->set(0,2);
        $card2->set(10,3);
        $player = new Player;
        $player->getCard($card1);
        $player->getCard($card2);
        
        $this->assertEquals(21, $player->points());
    }

    /**
     * tests if object Player can correct get points
     * correct
     * @return void
     */
    public function testGetHand(): void
    {
        $card1 = new CardGraphics();
        $card2 = new CardGraphics();
        $card1->set(0,2);
        $card2->set(10,3);
        $player = new Player;
        $player->getCard($card1);
        $player->getCard($card2);
        $hand = $player->getHand();
        $expected = "img/img/cards/English_pattern_ace_of_diamonds.svg";
        $this->assertContains($expected, $hand['hand']);
        $this->assertEquals(true, $hand['soft']);
        $this->assertEquals(21, $hand['points']);
        //$this->assertEquals('wait', $hand['status']);
    }

    /**
     * tests if object Player can correct get points
     * correct
     * @return void
     */
    public function testBlackJack(): void
    {
        $card1 = new CardGraphics();
        $card2 = new CardGraphics();
        $card1->set(0,2);
        $card2->set(10,3);
        $player = new Player;
        $player->getCard($card1);
        $player->getCard($card2);
        
        $this->assertTrue($player->blackJack());
    }

    /**
     * Test insurance of the plauer
     */
    public function testInsurance(): void
    {
        $player = new Player;
        $this->assertFalse($player->insurance());

        $player->insure();
        $this->assertTrue($player->insurance());
    }

    /**
     * Test split hand
     * @return void
     */
    public function testSpli():void
    {
        $player = new Player;
        $this->assertEquals(null, $player->canSplit());

        $card1 = new CardGraphics();
        $card2 = new CardGraphics();
        $card1->set(0,2);
        $card2->set(10,3);
        
        $player->getCard($card1);
        $player->getCard($card2);
        $this->assertEquals(null, $player->canSplit());

        $player2 = new Player;
        $player2->getCard($card1);
        $player2->getCard($card1);
        $this->assertNotEquals(null, $player2->canSplit());
    }

    /**
     * Test winGame
     */
    public function testWinGame(): void
    {
        $player = new Player;
        $player->doBet(2);

        $this->assertEquals(2, $player->getBet());

        $player->winGame(3, 2);
        $this->assertEquals(3, $player->getProfit());
        $this->assertEquals('win', $player->getStatus());
    }

     /**
     * Test loosGame
     */
    public function testLoosGame(): void
    {
        $player = new Player;
        $player->doBet(2);

        $this->assertEquals(2, $player->getBet());

        $player->loosGame(3, 2);
        $this->assertEquals(-3, $player->getProfit());
        $this->assertEquals('loos', $player->getStatus());
    }

    /**
     * Test SplitHand
     */
    public function testSplitHandCanSplit(): void
    {
        $player = new Player;
        $player->doBet(2);
        $player->setName('Joe');
        $card1 = new CardGraphics();
        $card2 = new CardGraphics();
        $card1->set(0,2);
        $card2->set(0,3);
        
        $player->getCard($card1);
        $player->getCard($card2);

        $this->assertEquals(true, $player->canSplit());

        $result = $player->splitHand();
        $this->assertEquals('Joe-2', $result['name']);
        $this->assertEquals(2, $result['bet']);
        $this->assertInstanceOf("\App\BlackJack\CardGraphics", $result['card']);
        $this->assertEquals('Joe-1', $player->getName());
        $this->assertEquals(1, $player->countCards());
    }

    /**
     * Test SplitHand cannot split
     */
    public function testSplitHandCanNot(): void
    {
        $player = new Player;
        $player->doBet(2);
        $player->setName('Joe');
        $card1 = new CardGraphics();
        $card2 = new CardGraphics();
        $card1->set(0,2);
        $card2->set(10,3);
        
        $player->getCard($card1);
        $player->getCard($card2);

        $this->assertEquals(false, $player->canSplit());
        $this->assertEquals(false, $player->splitHand());

        $result = $player->splitHand();
        $this->assertEquals(null, $result);
        
        $this->assertEquals('Joe', $player->getName());
        $this->assertEquals(2, $player->countCards());
    }
}