<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Hand from BlackJack.
 * 
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
        $this->assertInstanceOf("\App\BlackJack\Hand", $hand);

        $resultat = $hand->toArray();
        $expected = ['points' => 0, 'cards' => [], 'soft' => false];
        $this->assertEquals($expected, $resultat);
    }

    /**
     * Construct object and verify it get correct points.
     * 
     * @return void
     */
    public function testGetPoints(): void
    {
        $hand = new Hand();
        $card = new CardGraphics();
        $card->set(4, 2);

        $hand->addCard($card);
        $hand->addCard($card);
        $this->assertEquals($card->getValue() * 2, $hand->getPoints());

        $hand = new Hand();
        $card1 = new CardGraphics();
        $card1->set(0, 2);
        $card2 = new CardGraphics();
        $card2->set(11, 2);
        $hand->addCard($card1);
        $hand->addCard($card2);
        $this->assertEquals(21, $hand->getPoints());
        $this->assertTrue($hand->soft());

        $hand = new Hand();
        $card1 = new CardGraphics();
        $card1->set(0, 2);
        $card2 = new CardGraphics();
        $card2->set(8, 2);
        $card3 = new CardGraphics();
        $card3->set(7, 2);
        $hand->addCard($card3);
        $hand->addCard($card2);
        $hand->addCard($card1);
        $this->assertEquals(18, $hand->getPoints());
        $this->assertTrue($hand->soft());
    }

    /**
     * Construct object and verify it get blackJAck points.
     * 
     * @return void
     */
    public function testGetBlackJack(): void
    {
        $hand = new Hand();
        $card1 = new CardGraphics();
        $card1->set(0, 2);
        $card2 = new CardGraphics();
        $card2->set(11, 2);
        $hand->addCard($card1);
        $this->assertTrue($hand->soft());
        $hand->addCard($card2);
        $this->assertEquals(21, $hand->getPoints());
        $this->assertTrue($hand->blackJack());
    }

    /**
     * Construct object and verify of toString method werks.
     * @return void
     */
    public function testGetCardFaces(): void
    {
        $hand = new Hand();
        $card = $this->createMock(CardGraphics::class);
        $card->method('getFace')->willReturn('jack');
       
        $hand->addCard($card);
        $hand->addCard($card);
        $res = $hand->getCardFaces();
        $exp = $card->getFace();
        $this->assertNotEmpty($res);
        $this->assertEquals(2, count($res));
        $this->assertEquals($exp, $res[0]);
    }

    /**
     * Construct object and verify of toArray method werks.
     * @return void
     */
    public function testToArray(): void
    {
        $hand = new Hand();
        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);
        // Configure mock objects.
        $card1->method('chose')->wilLReturn([2, 3]);
        $card1->method('toArray')->willReturn([2, 3]);
        $card1->method('getValue')->willReturn(2);
        $card1->method('chose')->wilLReturn([10, 2]);   
        $card2->method('toArray')->willReturn([10, 2]);
        $card2->method('getValue')->willReturn(10);

        $hand->addCard($card1);
        $hand->addCard($card2);
        $res = $hand->toArray();
        $exp = $card1->toArray();

        $this->assertNotEmpty($res);
        $this->assertEquals(3, count($res));
        $this->assertEquals($exp, $res['cards'][0]);
        $this->assertEquals(12, $res['points']);
    }

    /**
     * Construct object and verify 
     * method getHand with help of mocked CardGraphics objekt.
     * @return void
     */
    public function testGetHand(): void
    {
        $hand = new Hand();
        // Create a stub for the CardGraphics class.
        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);
        // Configure the stub.
        $card1->method('getUrl')
            ->willReturn("mg/img/cards/English_pattern_2_of_diamonds.svg");
            
        $card2->method('getUrl')
            ->willReturn("mg/img/cards/English_pattern_jack_of_hards.svg");
            
        $hand->addCard($card1);
        $hand->addCard($card2);
        $res = $hand->getHand();
        $exp = ["mg/img/cards/English_pattern_2_of_diamonds.svg", "mg/img/cards/English_pattern_jack_of_hards.svg"];
        $this->assertNotEmpty($res);
        $this->assertEquals(2, count($res));
        $this->assertEquals($exp, $res);
    }

    // /**
    //  * Tests method set() 
    //  * @return void
    //  */
    // public function testSetMethod(): void
    // {
    //     $hand = new Hand();

    //     // input
    //     $inputArray = [
    //         'cards' => [
    //             [1, 3],
    //             [10, 2],
    //         ],
    //         'points' => 12,
    //         'soft' => false,
    //     ];

    //     $result = $hand->set($inputArray);

    //     // Assertions
    //     $this->assertInstanceOf(Hand::class, $result);
    //     $resultat = $hand->getHand();
    //     $this->assertCount(2,  $resultat);
        
    //     $expToString = ['img/img/cards/English_pattern_2_of_clubs.svg',
    //      'img/img/cards/English_pattern_jack_of_diamonds.svg'];
    //     // Check each card in cards property
    //     $this->assertIsArray($resultat);
    //     $this->assertEquals($expToString, $resultat);

    //     // Check points property
    //     $this->assertEquals(12, $hand->getPoints());
    // }

    /**
     * Test if hand retirn proper soft
     * 
     * @return void
     */
    public function testSoft(): void
    {
        $hand = new Hand();
        $this->assertEquals(false, $hand->soft());

        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);

        $card1->method('chose')->wilLReturn([2, 3]);
        $card1->method('toArray')->willReturn([2, 3]);
        $card1->method('getValue')->willReturn(2);
        $card1->method('chose')->wilLReturn([0, 2]);   
        $card2->method('toArray')->willReturn([0, 2]);
        $card2->method('getFace')->willReturn('ace');

        $hand->addCard($card1);
        $this->assertEquals(false, $hand->soft());

        $hand->addCard($card2);
        $this->assertEquals(true, $hand->soft());
    }

    /**
     * Test if hand retirn proper blackJAck()
     * 
     * @return void
     */
    public function testBlackJackFalse(): void
    {
        $hand = new Hand();
        $this->assertEquals(false, $hand->blackJack());

        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);
        
        $card1->method('chose')->wilLReturn([2, 3]);
        $card1->method('toArray')->willReturn([2, 3]);
        $card1->method('getValue')->willReturn(2);
        $card1->method('chose')->wilLReturn([0, 2]);   
        $card2->method('toArray')->willReturn([0, 2]);
        $card2->method('getFace')->willReturn('ace');

        $hand->addCard($card1);
        $this->assertEquals(false, $hand->blackJack());

        $hand->addCard($card2);
        $this->assertEquals(false, $hand->blackJack());
        $hand->addCard($card1);
        $this->assertEquals(false, $hand->blackJack());
    }

    /**
     * Test if hand retirn proper blackJAck()
     * 
     * @return void
     */
    public function testBlackJackTrue(): void
    {
        $hand = new Hand();
        $this->assertEquals(false, $hand->blackJack());

        $card1 = $this->createMock(CardGraphics::class);
        $card2 = $this->createMock(CardGraphics::class);
        
        $card1->method('chose')->wilLReturn([10, 3]);
        $card1->method('toArray')->willReturn([10, 3]);
        $card1->method('getValue')->willReturn(2);
        $card1->method('chose')->wilLReturn([0, 2]);   
        $card2->method('toArray')->willReturn([0, 2]);
        $card2->method('getFace')->willReturn('ace');

        $hand->addCard($card1);
        $this->assertEquals(false, $hand->blackJack());

        $hand->addCard($card2);
        $this->assertEquals(false, $hand->blackJack());
        $hand->addCard($card1);
        $this->assertEquals(false, $hand->blackJack());
    }

    /**
     * Test split hand
     * 
     * @return void
     */
    public function testSplitHand(): void
    {
        $hand = new Hand();

        $card1 = new CardGraphics();
        $card1->set(8, 1);
        $card2 = new CardGraphics();
        $card2->set(8, 2);
    
        $hand->addCard($card1);
        $hand->addCard($card2);

        $card = $hand->split();
        $this->assertInstanceOf("\App\BlackJack\CardGraphics", $card);
        
        $this->assertEquals('9', $card->getFace());
        $this->assertEquals(9, $card->getValue());

    }
}