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

        $game->bankDeal($player);
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

        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul'));
    }

    /**
     * Get activ players
     * 
     * @return void
     */
    public function testGetPlaying(): void
    {
        $game = new Game();

        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);

        $result = $game->getPlaying();
        $this->assertInstanceOf("\App\BlackJack\Player", $result['Jul']);
        $status = $result['Jul']->getStatus();
        $this->assertEquals('play', $status);
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
     * Teast bankBet
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
        $points = $game->bankDeal($player);
        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul'));
        $this->assertTrue($points > 0);
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
        $game = new GameInterface();
        $bank= $this->createMock(Bank::class);
        $bank->method('points')->willReturn(22);
        $bank->method('blackJack')->willReturn(true);
        $game->setBank($bank);
        $newBank = $game->getBank();
        $this->assertInstanceOf("\App\BlackJack\Bank", $newBank);
        $this->assertTrue($newBank->blackJack());
        $this->assertEquals(22, $newBank->points());
    }


    /**
     * Teast gameStatus
     * 
     * @return void
     */
    public function testGameStatus(): void
    {
        $game = new Game();
        $bank = new Bank();

        $card = new CardGraphics();
        $card->set(1, 2);
        $card1 = new CardGraphics();
        $card1->set(0, 2);
        $card2 = new CardGraphics();
        $card2->set(9, 2);
        $card3 = new CardGraphics();
        $card3->set(5, 2);
        // Configure the stubDesk.
        $desk = $this->createMock(Desk::class);
        $desk->method('takeCard')->willReturn($card1);

        // Configure the stubDesk1.
        $desk1 = $this->createMock(Desk::class);
        $desk1->method('takeCard')->willReturn($card2);
        
        $bank->takeCard($desk);
        $bank->takeCard($desk1);
        $game->setBank($bank);
        $status = $game->gameStatus();
       
        $this->assertEquals('Black Jack', $status);
    }

        /**
     * Teast gameStatus get no playing
     * 
     * @return void
     */
    public function testGameStatusNoPlaying(): void
    {
        $game = new Game();
        $bank = new Bank();

        $game->setBank($bank);
        $status = $game->gameStatus();
       
        //$this->assertTrue($game->getBank()->getStatus() === 'play' && ($game->getPlaying()) === 0);
        $this->assertEquals(0, count($game->getPlaying()));
        $this->assertEquals('no playing', $status);
    }
}