<?php

namespace App\BlackJack;
//use App\BlackJack\CardGraphics;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from BlackJack.
 */
class BankTest extends TestCase
{
    /**
     * Construct object and verify it is a Bank object.
     * It also check if all properties set as expected
     * It tests getBet(), getProfit() and getHand()
     */
    public function testCreateObject(): void
    {
        $bank = new Bank();
        $this->assertInstanceOf("\App\BlackJack\Bank", $bank);

        $hand = $bank->getHand();
        //$exp = ['points' => 0, 'cards' => []];
        $this->assertEquals(0, $bank->getBet());
        $this->assertEquals(0, $bank->getProfit());
        $expected = [
            'bet' => 0,
            'hand' => [],
            'points' => 0,
            'status' => 'play',
            'blackJack' => false,
            'profit' => 0,
            'soft' => false
        ];
        $this->assertEquals($expected, $hand);
    }

    /**
     * test if object bank can use getCard()
     * and getPoints()
     * using mock CardGraphics object
     */
    public function testGetCard(): void
    {
        $bank = new Bank;
        // Create a mock variable for the CardGraphics class.
        $card = new CardGraphics();
        $card->set(1, 2);
        $mockDesk = $this->createMock(Desk::class);
        $mockDesk->method('takeCard')->willReturn($card);
        // Configure the stub.
    
        //bank get cards
        $bank->takeCard($mockDesk);
        $bank->takeCard($mockDesk);
        $this->assertEquals(4, $bank->points());
    }

    /**
     * tests if object bank can use getdoBet()
     *
     */
    public function testDoBet(): void
    {
        $bank = new Bank;
        $bank->doBet(10);
        $res = $bank->getBet();
        $this->assertEquals(10, $res);
    }

    /**
     * tests if object bank function takeCard(Desk $desk)
     * correct functioning
     */
    public function testTakeCardIfFat(): void
    {
        $bank = new Bank;
        $card = new CardGraphics();
        $card->set(7, 2);
        $mockDesk = $this->createMock(Desk::class);

        $mockDesk->method('takeCard')->willReturn($card);
        $result1 = $bank->takeCard($mockDesk);
        $this->assertTrue($result1);
        $result2 = $bank->takeCard($mockDesk);
        $this->assertTrue($result2);
        $result3 = $bank->takeCard($mockDesk);
        $this->assertFalse($result3);
        
        $status = $bank->getStatus();
        $points = $bank->points();
        $this->assertEquals('fat', $status);
        $this->assertTrue($points > 21);
        $result4 = $bank->takeCard($mockDesk);
        $this->assertFalse($result4);       
        
        $bank1 = new Bank();
        $res = $bank1->setStatus('fat');
        $this->assertequals($res, 'fat');
    }

    /**
     * tests if object bank function takeCard(Desk $desk)
     * correct functioning
     */
    public function testTakeCardWin(): void
    {
        $bank = new Bank;
        $card = new CardGraphics();
        $hand = new Hand();
        $card->set(6, 3);
        $this->assertEquals('7', $card->getFace()); 
        $this->assertEquals(7, $card->getValue()); 
        $card1 = new CardGraphics();
        $card1->set(10, 1);
        $card2 = new CardGraphics();
        $card2->set(6, 0);
        $this->assertEquals('7', $card2->getFace()); 
        $this->assertEquals(7, $card2->getValue()); 
        $card3 = new CardGraphics();
        $card3->set(6, 2);
        $this->assertEquals('7', $card3->getFace()); 
        $this->assertEquals(7, $card3->getValue()); 
        $hand->addCard($card);
        $hand->addCard($card3);
        $hand->addCard($card2);
        $pointsHand = $hand->getPoints();
        $this->assertEquals(21, $pointsHand);
        $this->assertEquals('jack', $card1->getFace());
        $this->assertEquals(10, $card1->getValue()); 
        //$mockCard = $this->createMock(CardGraphics::class);
        $mockDesk = $this->createMock(Desk::class);

        $mockDesk->method('takeCard')->willReturn($card);
        $result1 = $bank->takeCard($mockDesk);
        $mockDesk->method('takeCard')->willReturn($card3);
        $result1 = $bank->takeCard($mockDesk);
        $mockDesk->method('takeCard')->willReturn($card2);
        $result1 = $bank->takeCard($mockDesk);
    
        $this->assertEquals(3, count($bank->getHand()['hand']));
        
        $points = $bank->points();
        $status = $bank->getStatus();
        $this->assertEquals('win', $status);
        $this->assertTrue($points === 21);
    }

    /**
     * tests if object bank function takeCard(Desk $desk)
     * correct functioning
     */
    public function testTakeCardIfBlackJack(): void
    {
        $bank = new Bank();
        $player = new Player();
        $card = new CardGraphics();
        //$hand = new Hand();
        $card->set(0, 2);
        // $this->assertEquals('ace', $card->getFace()); 
        // $this->assertEquals(0, $card->getValue()); 
        $card1 = new CardGraphics();
        $card1->set(11, 1);
        // $hand->addCard($card);
        // $hand->addCard($card1);
        //$pointsHand = $hand->getPoints();
        //$this->assertEquals(21, $pointsHand);
        // $this->assertEquals('jack', $card1->getFace());
        // $this->assertEquals(10, $card1->getValue()); 
        $mockDesk = $this->createMock(Desk::class);
        $mockDesk1 = $this->createMock(Desk::class);


        $mockDesk->method('takeCard')->willReturn($card1);
        $result1 = $bank->takeCard($mockDesk);
    
        $this->assertTrue($result1);
        $mockDesk1->method('takeCard')->willReturn($card);
        $result2 = $bank->takeCard($mockDesk1);
        $player->getCard($card);
        $this->assertTrue(21 === $player->getCard($card1));
        
        $points = $bank->points();
        $hand = $bank->getHand();
        $this->assertEquals($points, 21);
        $this->assertTrue(count($hand['hand']) === 2);
        //$bank->setStatus('Black Jack');
        $status = $bank->getStatus();
        $this->assertTrue($bank->blackJack());      
        $this->assertEquals('Black Jack', $status);      
    }

    /**
     * test if object bank can use takeCard()
     * and getPoints()
     * using mock CardGraphics object and mock Desk object
     */
    public function testTakeCard2(): void
    {
        $bank = new Bank;
        // Create a mock for the CardGraphics class.
        $mockCard = $this->createMock(CardGraphics::class);
        $mockDesk = $this->createMock(Desk::class);

        // Configure the mock.
        $mockCard->method('getValue')
            ->willReturn(2);

        // Configure the mockDesk.
        $mockDesk->method('takeCard')
            ->willReturn($mockCard);

        //bank get cards
        $bank->takeCard($mockDesk);
        $this->assertEquals(2, $bank->points());
    }

    /**
     * test if object bank can use takeCard()
     * and getPoints()
     * using mock CardGraphics object and mock Desk object
     */
    public function testDealCards(): void
    {
        $bank = new Bank;
        // Create a stub for the CardGraphics class.
        $card = new CardGraphics();
        $card->set(1, 2);
        $desk = $this->createMock(Desk::class);
        $player = new Player;

        // Configure the stubDesk.
        $desk->method('takeCard')->willReturn($card);

        //bank deal cards
        $bank->dealCards($desk, $player);
        $this->assertEquals(2, $player->points());
    }
}
