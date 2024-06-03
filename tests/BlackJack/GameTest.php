<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\BlackJack\Player;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for class Card from Game.
 */
class GameTest extends TestCase
{
    /**
     * Construct object and verify it is a Game object.
     * It also check if all properties set as expected
     * It tests getBet(), getProfit() and getHand()
     * 
     * @return void
     */
    public function testCreateObject(): void
    {
        $game = new Game();
        $this->assertInstanceOf("\App\BlackJack\Game", $game);
        $this->assertEquals([], $game->getPlayers());

        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(['Jul' => $player], $game->getPlayers());
        //$this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul'));
        $name = $game->findPlayer('Jul')->getName();
        $this->assertEquals('Jul', $name);

        $game->bankDeal($player, 'Jul');
        $this->assertEquals(51, count($game->getDesk()));
    }   

     /**
     * Construct object and verify it is a Game object.
     * It also check if all properties set as expected
     * It tests getBet(), getProfit() and getHand()
     * 
     * @return void
     */
    public function testAddPlaying(): void
    {
        $game = new Game();
        $this->assertEquals(0, count($game->getPlayers()));
        $this->assertEquals(0, count($game->getPlaying()));
        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(1, count($game->getPlayers()));
        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul'));
    }
    
    /**
     * Find players
     * 
     * @return void
     */
    public function testFindPlayer(): void
    {
        $game = new Game();

        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertTrue(count($game->getPlaying()) === 1);

        while(count($game->getPlayers()) === 1) {
            $game->bankDeal($player, 'Jul');
        }

        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul'));
        $this->assertTrue(count($game->getPlaying()) === 0);
    }

    /**
     * Teast shuffle
     * 
     * @return void
     */
    public function testShuffleDesk(): void
    {
        $game = new Game();
        $firstDesk = $game->getDesk();

        $game->shuffleDesk();
        $secondDesk = $game->getDesk();
        $this->assertNotEquals($firstDesk, $secondDesk);
    }

    /**
     * Teast shuffle
     * 
     * @return void
     */
    public function testBankBet(): void
    {
        $game = new Game();
        $result = $game->bankBet();
        $this->assertIsInt($result);
        $this->assertLessThanOrEqual(30, $result);
    }

    //  /**
    //  * Teast newCardToBank
    //  * 
    //  * @return void
    //  */
    // public function testNewCardToBank(): void
    // {
    //     $game = new Game();
    //     $result = $game->newCardToBank();
    //     $this->assertEquals(10, $result);
    //     $player = $this->createMock(Player::class);
    //     $player->method('points')->willReturn(2);
    //     $player->method('getName')->willReturn('Jul');
    //     $player->method('countCards')->willReturn(0);
    //     $game->addPlaying('Jul', $player);
    //     $result = $game->newCardToBank();
    //     $this->assertEquals(0, $result);
    // }

    /**
     * Teast bankDeal
     * 
     * @return void
     */
    public function testBankDeal(): void
    {
        $game = new Game();
        $player = $this->createMock(Player::class);
        $player->method('points')->willReturn(22);
        $player->method('getName')->willReturn('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(1, count($game->getPlayers()));
        $this->assertEquals(0, count($game->getPlaying()));
        $game->bankBet();
        $game->bankDeal($player, 'Jul');
        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul'));
        $this->assertEquals(1, count($game->getPlayers()));
    }

    /**
     * Teast getBank
     * 
     * @return void
     */
    public function testGetBank(): void
    {
        $game = new Game();
        $bank = $game->getBank();
        $this->assertInstanceOf("\App\BlackJack\Bank", $bank);
        $this->assertEquals(0, $bank->getBet());
    }

    /**
     * Teast setBank
     * 
     * @return void
     */
    public function testSetBank(): void
    {
        $game = new Game();
        $bank= $this->createMock(Bank::class);
        $bank->method('points')->willReturn(22);
        $bank->method('blackJack')->willReturn(true);
        $game->setBank($bank);
        $newBank = $game->getBank();
        $this->assertInstanceOf("\App\BlackJack\Bank", $newBank);
        $this->assertTrue($newBank->blackJack());
        $this->assertEquals(22, $newBank->points());
    }
}