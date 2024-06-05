<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for class Card from Game.
 */
class GameInterfaceTest extends TestCase
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
        $game = new GameInterface();
        $this->assertInstanceOf("\App\BlackJack\GameInterface", $game);
        $this->assertEquals([], $game->getPlayers());

        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(['Jul' => $player], $game->getPlayers());
        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul'));
        $name = $game->findPlayer('Jul')->getName();
        $this->assertEquals('Jul', $name);

        $game->bankDeal($player);
        $this->assertEquals(51, count($game->getDesk()));
    }

    /**
     * Teast bankDeal
     * 
     * @return void
     */
    public function testBankDeal(): void
    {
        $game = new GameInterface();
        $player = $this->createMock(Player::class);
        $player->method('points')->willReturn(22);
        $player->method('getName')->willReturn('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(1, count($game->getPlayers()));
        $game->bankBet();
        $game->bankDeal($player);
        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul'));
        $this->assertEquals(1, count($game->getPlayers()));
    }

    /**
     * Teast firstDeal
     * 
     * @return void
     */
    public function testFirstDeal(): void
    {
        $game = new GameInterface();
        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertTrue(count($game->getPlayers()) === 1);
        $this->assertTrue(count($game->getPlaying()) === 1);
        $game->firstDeal();
        $playerHand = $game->findPlayer('Jul',)->getHand();
        $this->assertequals(2, count($playerHand['hand']));
    }

    /**
     * Teast firstDeal
     * 
     * @return void
     */
    public function testFirstDealBlackJack(): void
    {
        $game = new GameInterface();
        $player = $this->createMock(Player::class);
        $player->method('points')->willReturn(21);
        $player->method('getName')->willReturn('Jul');
        $player->method('blackJack')->willReturn(true);
        $game->addPlaying('Jul', $player);
        $this->assertEquals(0, count($game->getPlaying()));
        $this->assertEquals(1, count($game->getPlayers()));
        $game->firstDeal();
        if($game->getBank()->points() < 10){
            $this->assertEquals(0, count($game->getPlaying()));
            $this->assertEquals(1 ,count($game->getPlayers()));
        } else {
            $this->assertEquals(1, count($game->getPlayers()));
            $this->assertEquals(0, count($game->getPlaying()));
        }
    }

    /**
     * Teast firstDeal
     * 
     * @return void
     */
    public function testGetGame(): void
    {
        $game = new GameInterface();
        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(1, count($game->getPlayers()));
        $this->assertEquals(1, count($game->getPlaying()));
        $game->firstDeal();
        $playerHand = $game->findPlayer('Jul')->getHand();
        $gameArray = $game->getGame();
        //$game->bankDeal($player, 'Jul');
        //$this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul', 'ready'));
        $this->assertEquals(2, count($playerHand['hand']));
        $this->assertEquals(count($gameArray['players']['Jul']['hand']), count($playerHand['hand']));
    }
}