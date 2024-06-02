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
        $this->assertEquals([], $game->getPlayers('playing'));

        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(['Jul' => $player], $game->getPlayers('playing'));
        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul', 'playing'));
        $name = $game->findPlayer('Jul', 'playing')->getName();
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
        $this->assertInstanceOf("\App\BlackJack\Game", $game);
        $this->assertEquals(0, count($game->getPlayers('playing')));
        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(1, count($game->getPlayers('playing')));
        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul', 'playing'));
        $this->assertEquals(1, count($game->getPlayers('playing')));
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
        $this->assertTrue(count($game->getPlayers('playing')) > 0);

        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul', 'playing'));

        while(count($game->getPlayers('ready')) === 0) {
            $game->bankDeal($player, 'Jul');
        }

        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul', 'ready'));
        $this->assertEquals(null, $game->findPlayer('Jul', 'wait'));
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

     /**
     * Teast newCardToBank
     * 
     * @return void
     */
    public function testNewCardToBank(): void
    {
        $game = new Game();
        $result = $game->newCardToBank();
        $this->assertEquals(10, $result);
        $player = $this->createMock(Player::class);
        $player->method('points')->willReturn(2);
        $player->method('getName')->willReturn('Jul');
        $player->method('countCards')->willReturn(0);
        $game->addPlaying('Jul', $player);
        $result = $game->newCardToBank();
        $this->assertEquals(0, $result);
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
        $this->assertEquals(1, count($game->getPlayers('playing')));
        $this->assertEquals(0, count($game->getPlayers('ready')));
        $game->bankBet();
        $game->bankDeal($player, 'Jul');
        $this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul', 'ready'));
        $this->assertEquals(1, count($game->getPlayers('ready')));
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

    /**
     * Teast firstDeal
     * 
     * @return void
     */
    public function testFirstDeal(): void
    {
        $game = new Game();
        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertTrue(count($game->getPlayers('playing')) === 1);
        $this->assertTrue(count($game->getPlayers('ready')) === 0);
        $game->firstDeal();
        $playerHand = $game->findPlayer('Jul','playing')->getHand();
        $this->assertequals(2, count($playerHand['hand']));
    }

    /**
     * Teast firstDeal
     * 
     * @return void
     */
    public function testFirstDealBlackJack(): void
    {
        $game = new Game();
        $player = $this->createMock(Player::class);
        $player->method('points')->willReturn(21);
        $player->method('getName')->willReturn('Jul');
        $player->method('blackJack')->willReturn(true);
        $game->addPlaying('Jul', $player);
        $this->assertEquals(1, count($game->getPlayers('playing')));
        $this->assertEquals(0, count($game->getPlayers('ready')));
        $game->firstDeal();
        if($game->getBank()->points() < 10){
            $this->assertEquals(0, count($game->getPlayers('playing')));
            $this->assertEquals(1 ,count($game->getPlayers('ready')));
        } else {
            $this->assertEquals(1, count($game->getPlayers('playing')));
            $this->assertEquals(0, count($game->getPlayers('ready')));
        }
    }

    /**
     * Teast firstDeal
     * 
     * @return void
     */
    public function testGetGame(): void
    {
        $game = new Game();
        $player = new Player();
        $player->setName('Jul');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(1, count($game->getPlayers('playing')));
        $this->assertEquals(0, count($game->getPlayers('ready')));
        $game->firstDeal();
        $playerHand = $game->findPlayer('Jul','playing')->getHand();
        $gameArray = $game->getGame();
        //$game->bankDeal($player, 'Jul');
        //$this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul', 'ready'));
        $this->assertEquals(2, count($playerHand['hand']));
        $this->assertEquals(count($gameArray['players']['Jul']['hand']), count($playerHand['hand']));
    }

    /**
     * Teast firstDeal
     * 
     * @return void
     */
    public function testGetGameMovePlayerToready(): void
    {
        $game = new Game();
        $player = new Player();
        $player->setName('Jul');
        $player->setStatus('fat');
        $game->addPlaying('Jul', $player);
        $this->assertEquals(1, count($game->getPlayers('playing')));
        $this->assertEquals(0, count($game->getPlayers('ready')));
        $gameArray = $game->getGame();
        $this->assertEquals(0, count($game->getPlayers('playing')));
        $this->assertEquals(1, count($game->getPlayers('ready')));
        $playerHand = $game->findPlayer('Jul','ready')->getHand();
        
        //$game->bankDeal($player, 'Jul');
        //$this->assertInstanceOf("\App\BlackJack\Player", $game->findPlayer('Jul', 'ready'));
        $this->assertEquals(0, count($playerHand['hand']));
        $this->assertEquals(count($gameArray['players']['Jul']['hand']), count($playerHand['hand']));
    }

    /**
     * Teast finish()
     * 
     * @return void
     */
    public function testFinish(): void
    {
        // finish if playing contain no players
        $game1 = new Game();
        $resultat1 = $game1->finish();
        $this->assertTrue($resultat1[1]);
        $this->assertEquals('count playing', $resultat1[0]);
        
        // finish if bank get black jack
        $game2 = new Game();
        $bank = $this->createMock(Bank::class);
        $bank->method('points')->willReturn(10);
        $bank->method('blackJack')->willReturn(true);
        $game2->setBank($bank);

        $resultat2 = $game2->finish();
        $this->assertTrue($resultat2[1]);
        $this->assertEquals('bank Black Jack', $resultat2[0]);
        
        // finish if bank get fat
        $game3 = new Game();
        $bank = $this->createMock(Bank::class);
        $bank->method('points')->willReturn(10);
        $bank->method('blackJack')->willReturn(false);
        $bank->method('getStatus')->willReturn('fat');
        $game3->setBank($bank);

        $resultat3 = $game3->finish();
        $this->assertTrue($resultat3[1]);
        $this->assertEquals('bank fat', $resultat3[0]);


        // not finish if bank is not fat
        $game4 = new Game();
        $player = new Player();
        $player->setName('Jul');
        $game4->addPlaying('Jul', $player);
        $bank = $this->createMock(Bank::class);
        $bank->method('points')->willReturn(19);
        $bank->method('blackJack')->willReturn(false);
        $bank->method('getStatus')->willReturn('play');
        $game4->setBank($bank);

        $resultat4 = $game4->finish();
        $this->assertFalse($resultat4[1]);
        $this->assertEquals('not finish', $resultat4[0]);
    }

    /**
     * Teast countWinst Bank has Black Jack
     * 
     * @return void
     */
    public function testCountWinstBankBJ(): void
    {
        
        //cards
        $card1 = new CardGraphics();
        $card1->set(0, 1);
        $card2 = new CardGraphics();
        $card2->set(10, 1);
        $card3 = new CardGraphics();
        $card3->set(6, 2);
        $card4 = new CardGraphics();
        $card4->set(7, 2);
        $card5 = new CardGraphics();
        $card5->set(8, 3);
        $card5 = new CardGraphics();
        $card5->set(4, 0);
        //players

        //1
        $player1 = new Player();
        $player1->setName('Jul');
        $player1->doBet(2);
        $player1->getCard($card2);
        $player1->getCard($card3);
        $player1->getCard($card4);
        $player1->setStatus('fat');
        //2
        $player2 = new Player();
        $player2->setName('Jana');
        $player2->doBet(2);
        $player2->getCard($card2);
        $player2->getCard($card5);
        $player2->insure();
        $player2->setStatus('ready');
        //3
        $player3 = new Player();
        $player3->setName('Dirk');
        $player3->doBet(2);
        $player3->getCard($card1);
        $player3->getCard($card2);
        $player3->setStatus('wait');
        //4
        $player4 = new Player();
        $player4->setName('Per');
        $player4->doBet(2);
        $player4->getCard($card1);
        $player4->getCard($card3);
        $player4->setStatus('ready');
        //5
        $player5 = new Player();
        $player5->setName('Björn');
        $player5->doBet(2);
        $player5->getCard($card1);
        $player5->getCard($card4);
       
        //6
        $player6 = new Player();
        $player6->setName('Sten');
        $player6->doBet(2);
        $player6->getCard($card1);
        $player6->getCard($card4);
        $player6->insure();

        /**game case bank has Black Jack 
         * Player 1 Jul profit -2
         * Player 2 Jana profit -1
         * Player 3 Dirk profit 0
         * Player 4 Per profit -2
         * Player 5 Björn profit -2
         * Player 6 Sten profit -1
         */
        $game = new Game();
        $game->addPlaying('Jul', $player1);
        $game->addPlaying('Jana', $player2);
        $game->addPlaying('Dirk', $player3);
        $game->addPlaying('Per', $player4);
        $game->addPlaying('Björn', $player5);
        $game->addPlaying('Sten', $player6);
        $game->getBank()->setStatus('Black Jack');
        $game->getGame();

        $game->countWinst();

        $this->assertEquals(-2, $game->findPlayer('Jul', 'ready')->getProfit());
        $this->assertEquals(-1, $game->findPlayer('Jana', 'ready')->getProfit());
        $this->assertEquals(0, $game->findPlayer('Dirk', 'ready')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Per', 'ready')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Björn', 'playing')->getProfit());
        $this->assertEquals(-1, $game->findPlayer('Sten', 'playing')->getProfit());
    }

    /**
     * Teast countWinst bank status win
     * 
     * @return void
     */
    public function testCountWinstBAnkWin(): void
    {
        
        //cards
        $card1 = new CardGraphics();
        $card1->set(0, 1);
        $card2 = new CardGraphics();
        $card2->set(10, 1);
        $card3 = new CardGraphics();
        $card3->set(6, 2);
        $card4 = new CardGraphics();
        $card4->set(7, 2);
        $card5 = new CardGraphics();
        $card5->set(8, 3);
        $card5 = new CardGraphics();
        $card5->set(4, 0);
        $card6 = new CardGraphics();
        $card6->set(3, 0);
        //players

        //1
        $player1 = new Player();
        $player1->setName('Jul');
        $player1->doBet(2);
        $player1->getCard($card2);
        $player1->getCard($card3);
        $player1->getCard($card4);
        $player1->setStatus('fat');
        //2
        $player2 = new Player();
        $player2->setName('Jana');
        $player2->doBet(2);
        $player2->getCard($card2);
        $player2->getCard($card5);
        $player2->insure();
        $player2->setStatus('ready');
        //3
        $player3 = new Player();
        $player3->setName('Dirk');
        $player3->doBet(2);
        $player3->getCard($card1);
        $player3->getCard($card2);
        $player3->setStatus('wait');
        //4
        $player4 = new Player();
        $player4->setName('Per');
        $player4->doBet(2);
        $player4->getCard($card1);
        $player4->getCard($card3);
        $player4->setStatus('ready');
        //5
        $player5 = new Player();
        $player5->setName('Björn');
        $player5->doBet(2);
        $player5->getCard($card1);
        $player5->getCard($card4);
        //6
        $player6 = new Player();
        $player6->setName('Sten');
        $player6->doBet(2);
        $player6->getCard($card1);
        $player6->getCard($card4);
        $player6->insure();
        
        /**game case bank 'win' 
         * Player 1 profit -2
         * Player 2 profit -2
         * Player 3 profit 3
         * Player 4 profit -2
         * Player 5 profit -2
         * Player 6 profit -2
         */
        $game = new Game();
        $game->addPlaying('Jul', $player1);
        $game->addPlaying('Jana', $player2);
        $game->addPlaying('Dirk', $player3);
        $game->addPlaying('Per', $player4);
        $game->addPlaying('Björn', $player5);
        $game->addPlaying('Sten', $player6);
        $game->getBank()->setStatus('win');
        $game->getGame();

        $game->countWinst();
        
        
        $this->assertEquals(-2, $game->findPlayer('Jul', 'ready')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Jana', 'ready')->getProfit());
        $this->assertEquals(3, $game->findPlayer('Dirk', 'ready')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Per', 'ready')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Björn', 'playing')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Sten', 'playing')->getProfit());
    }

        /**
     * Teast countWinst bank status Play
     * 
     * @return void
     */
    public function testCountWinstBankPlaying(): void
    {
        
        //cards
        $card1 = new CardGraphics();
        $card1->set(0, 1);
        $card2 = new CardGraphics();
        $card2->set(10, 1);
        $card3 = new CardGraphics();
        $card3->set(6, 2);
        $card4 = new CardGraphics();
        $card4->set(7, 2);
        $card5 = new CardGraphics();
        $card5->set(8, 3);
        $card5 = new CardGraphics();
        $card5->set(4, 0);
        $card6 = new CardGraphics();
        $card6->set(1, 0);
        $card7 = new CardGraphics();
        $card7->set(2, 0);
        //players

        //1
        $player1 = new Player();
        $player1->setName('Jul');
        $player1->doBet(2);
        $player1->getCard($card2);
        $player1->getCard($card3);
        $player1->getCard($card4);
        $player1->setStatus('fat');
        //2
        $player2 = new Player();
        $player2->setName('Jana');
        $player2->doBet(2);
        $player2->getCard($card2);
        $player2->getCard($card5);
        $player2->insure();
        $player2->setStatus('ready');
        //3
        $player3 = new Player();
        $player3->setName('Dirk');
        $player3->doBet(2);
        $player3->getCard($card1);
        $player3->getCard($card2);
        $player3->setStatus('wait');
        //4
        $player4 = new Player();
        $player4->setName('Per');
        $player4->doBet(2);
        $player4->getCard($card2);
        $player4->getCard($card3);
        $player4->getCard($card6);
        $player4->setStatus('ready');
        //5
        $player5 = new Player();
        $player5->setName('Björn');
        $player5->doBet(2);
        $player5->getCard($card2);
        $player5->getCard($card4);
        $player5->getCard($card6);
        $player5->setStatus('ready');
        //6
        $player6 = new Player();
        $player6->setName('Sten');
        $player6->doBet(2);
        $player6->getCard($card1);
        $player6->getCard($card4);
        $player6->insure();
        //7
        $player7 = new Player();
        $player7->setName('Stan');
        $player7->doBet(2);
        $player7->getCard($card2);
        $player7->getCard($card3);
        $player7->getCard($card7);
        $player7->insure();
        
        /**game case bank 'play' and has 19 poins
         * Player 1 Jul profit -2 with fat and 25 points
         * Player 2 Jana profit -2 with ready and 15 points
         * Player 3 Dirk profit 3 with 21 points
         * Player 4 Per profit -2 with 19 points
         * Player 5 Björn profit 2 with 20 points
         * Player 6 profit -2
         * Player 7 profit 2
         */
        $game = new Game();
        $game->addPlaying('Jul', $player1);
        $game->addPlaying('Jana', $player2);
        $game->addPlaying('Dirk', $player3);
        $game->addPlaying('Per', $player4);
        $game->addPlaying('Björn', $player5);
        $game->addPlaying('Sten', $player6);
        $game->addPlaying('Stan', $player7);
        $game->getBank()->getCard($card1);
        $game->getBank()->getCard($card4);
        //$game->getBank()->getCard($card5);
        $game->getGame();

        $game->countWinst();

        $this->assertEquals(-2, $game->findPlayer('Jul', 'ready')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Jana', 'ready')->getProfit());
        $this->assertEquals(3, $game->findPlayer('Dirk', 'ready')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Per', 'ready')->getProfit());
        $this->assertEquals(2, $game->findPlayer('Björn', 'ready')->getProfit());
        $this->assertEquals(-2, $game->findPlayer('Sten', 'playing')->getProfit());
        $this->assertEquals(2, $game->findPlayer('Stan', 'playing')->getProfit());
        
    }
    
    /**
     * Teast countWinst bank status fat
     * 
     * @return void
     */
    public function testCountWinstBAnkFat(): void
    {
        
        //1
        $player1 = new Player();
        $player1->setName('Jul1');
        $player1->doBet(2);
        $player1->setStatus('fat');
        //2
        $player2 = new Player();
        $player2->setName('Jana1');
        $player2->doBet(2);
        $player2->setStatus('ready');
        //3
        $player3 = new Player();
        $player3->setName('Dirk1');
        $player3->doBet(2);
        $player3->setStatus('wait');
        //4
        $player4 = new Player();
        $player4->setName('Per1');
        $player4->doBet(2);
        $player4->setStatus('ready');
        //5
        $player5 = new Player();
        $player5->setName('Björn1');
        $player5->doBet(2);
        //6
        $player6 = new Player();
        $player6->setName('Sten1');
        $player6->doBet(2);
        $player6->insure();
        
        /**game case bank 'fat' 
         * * in array ready[]
         * Player 1 Jul1 profit -2
         * Player 2 Jana1 profit 2
         * Player 3 Dirk1 profit 3
         * Player 4 Per1 profit 2
         * * in array playing[]
         * Player 5 Björn1 profit 2
         * Player 6 Sten1 profit 2
         */
        $game = new Game();
        $game->addPlaying('Jul1', $player1);
        $game->addPlaying('Jana1', $player2);
        $game->addPlaying('Dirk1', $player3);
        $game->addPlaying('Per1', $player4);
        $game->addPlaying('Björn1', $player5);
        $game->addPlaying('Sten1', $player6);

        //set banksatatus as 'fat'
        $game->getBank()->setStatus('fat');

        // move players with status 'ready' to the array ready[]
        $game->getGame();

        $game->countWinst();
        
        //look if jana1 will win game iwth direct function winGame
        //$this->assertEquals(2, $game->findPlayer('Jana1', 'ready')->winGame(1, 1));
        $this->assertEquals('win', $game->findPlayer('Dirk1', 'ready')->getStatus());
        $this->assertEquals(0, $game->GetBank()->points());
        $this->assertEquals(-2, $game->findPlayer('Jul1', 'ready')->getProfit());
        $this->assertEquals(2, $game->findPlayer('Jana1', 'ready')->getProfit());
        $this->assertEquals(3, $game->findPlayer('Dirk1', 'ready')->getProfit());
        $this->assertEquals(2, $game->findPlayer('Per1', 'ready')->getProfit());
        $this->assertEquals(2, $game->findPlayer('Björn1', 'playing')->getProfit());
        $this->assertEquals(2, $game->findPlayer('Sten1', 'playing')->getProfit());
    }
}