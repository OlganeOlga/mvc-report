<?php

namespace App\Game21;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card from Game21.
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
        $this->assertInstanceOf("\App\Game21\Bank", $bank);

        $hand = $bank->getHand();
        //$exp = ['points' => 0, 'cards' => []];
        $this->assertEquals(0, $bank->getBet());
        $this->assertEquals(0, $bank->getProfit());
        $this->assertEquals([], $hand);
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
        $stub = $this->createMock(CardGraphics::class);

        // Configure the stub.
        $stub->method('getValue')
            ->willReturn(2);
        //bank get cards
        $bank->getCard(clone $stub);
        $bank->getCard(clone $stub);
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
     * tests if object bank can use toArray()
     * correct
     */
    public function testToArray(): void
    {
        $bank = new Bank;
        $bank->doBet(10);
        $exp = [
            'hand' => [
                'points' => 0,
                'cards' => []
            ],
            'bet' => 10,
            'profit' => 0
        ];
        
        $this->assertEquals($exp, $bank->toArray());
    }

    /**
     * test if object bank can use takeCard()
     * and getPoints()
     * using mock CardGraphics object and mock Desk object
     */
    public function testTakeCard(): void
    {
        $bank = new Bank;
        // Create a stub for the CardGraphics class.
        $stub = $this->createMock(CardGraphics::class);
        $stubDesk = $this->createMock(Desk::class);

        // Configure the stub.
        $stub->method('getValue')
            ->willReturn(2);

        // Configure the stubDesk.
        $stubDesk->method('takeCard')
            ->willReturn($stub);

        //bank get cards
        $bank->takeCards($stubDesk);
        $this->assertEquals(18, $bank->points());
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
        $card = $this->createMock(CardGraphics::class);
        $desk = $this->createMock(Desk::class);
        $player = new Player;

        // Configure the stub.
        $card->method('getValue')
            ->willReturn(2);

        // Configure the stubDesk.
        $desk->method('takeCard')
            ->willReturn($card);

        //bank get cards
        $bank->dealCards($desk, [$player]);
        $this->assertEquals(2, $player->points());
    }
}
