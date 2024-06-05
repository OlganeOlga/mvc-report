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

    // /**
    //  * Teast newCardToBank
    //  * 
    //  * @return void
    //  */
    // public function testNewCardToBank(): void
    // {
    //     $game = new GameInterface();
    //     $result = $game->newCardToBank();
    //     $this->assertEquals(true, $result);
        
    //     $player = new Player();
    //     $player->setName('Jul');
    //     $game->addPlaying('Jul', $player);
    //     $active = $game->getPlaying();
    //     $this->assertEquals(1, count($active));
    //     $result = $game->newCardToBank();
    //     $this->assertEquals(false, $result);
    // }

    // /**
    //  * Teast newCardToBank bank points < 17
    //  * 
    //  * @return void
    //  */
    // public function testNewCardToBanklessThan17(): void
    // {
    //     $game = new GameInterface();
    //     $player = $this->createMock(Player::class);
    //     $player->method('countCards')->willReturn(2);
    //     $player->method('getStatus')->willReturn('play');
    //     $player->method('getName')->willReturn('Jul');
    //     $game->addPlaying('Jul', $player);
    //     $bankP = $game->getBank()->points();
    //     $bankK = $game->getBank()->countCards();
    //     $this->assertEquals(0, $bankP);
    //     $active = $game->getPlaying();
    //     $this->assertEquals(1, count($active));
    //     $this->assertEquals(0, $bankK);
    //     $result = $game->newCardToBank();
    //     $this->assertEquals(true, $result);
    // }

    //     /**
    //  * Teast newCardToBank
    //  * 
    //  * @return void
    //  */
    // public function testNewCardToBank18(): void
    // {
    //     $game = new GameInterface();
    //     $bank = $this->createMock(Bank::class);
    //     $bank->method('points')->willReturn(18);
    //     $game->setBank($bank);
    //     $result = $game->newCardToBank();
    //     $this->assertEquals(false, $result);
    // }
   
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

    // /**
    //  * Teast finish()
    //  * 
    //  * @return void
    //  */
    // public function testFinish(): void
    // {
    //     // finish if playing contain no players
    //     $game1 = new GameInterface();
    //     $resultat1 = $game1->finish();
    //     $gstatus = $game1->gameStatus();
    //     $this->assertEquals($gstatus, 'no playing');
    //     $this->assertTrue($resultat1);
    //     $game1->newCardToBank();
    //     $bstatus = $game1->getBank()->getStatus();
       
    //     $status = $game1->gameStatus();

    //     $this->assertEquals('no playing', $status);
    //     $resultat2 = $game1->finish();
    //     $this->assertTrue($resultat2);

        
    //     // finish if bank get black jack
    //     $game2 = new GameInterface();
    //     $bank = $this->createMock(Bank::class);
    //     $bank->method('points')->willReturn(10);
    //     $bank->method('getStatus')->willReturn('Black Jack');
    //     $game2->setBank($bank);

    //     $player = $this->createMock(Player::class);
    //     $player->method('points')->willReturn(10);
    //     $player->method('getName')->willReturn('Jul');
    //     $player->method('getStatus')->willReturn('play');
    //     $game2->addPlaying('Jul', $player);

    //     $resultat2 = $game2->finish();
    //     $this->assertTrue($resultat2);
    //     $this->assertEquals(true, $resultat2);
        
    //     // finish if bank get fat
    //     $game3 = new GameInterface();
    //     $game = new GameInterface();
    //     $player = new Player();
    //     $player->setName('Jul');
    //     $game->addPlaying('Jul', $player);
    //     $bank = $this->createMock(Bank::class);
    //     $bank->method('points')->willReturn(22);
    //     $bank->method('blackJack')->willReturn(false);
    //     $bank->method('getStatus')->willReturn('fat');
    //     $game3->setBank($bank);

    //     $resultat3 = $game3->finish();
    //     $this->assertTrue($resultat3);
    //     $this->assertEquals(true, $resultat3);

        
    //     // finish if bank 21
    //     $game3 = new GameInterface();
    //     $game = new GameInterface();
    //     $player = new Player();
    //     $player->setName('Jul');
    //     $game->addPlaying('Jul', $player);
    //     $bank = $this->createMock(Bank::class);
    //     $bank->method('points')->willReturn(21);
    //     $bank->method('blackJack')->willReturn(false);
    //     $bank->method('getStatus')->willReturn('21');
    //     $game3->setBank($bank);

    //     $resultat3 = $game3->finish();
    //     $this->assertTrue($resultat3);
    //     $this->assertEquals(true, $resultat3);


    //     // not finish if bank is not fat
    //     $game4 = new GameInterface();
    //     $player = new Player();
    //     $player->setName('Jul');
    //     $game4->addPlaying('Jul', $player);
    //     $bank = $this->createMock(Bank::class);
    //     $bank->method('points')->willReturn(19);
    //     $bank->method('blackJack')->willReturn(false);
    //     $bank->method('getStatus')->willReturn('play');
    //     $game4->setBank($bank);

    //     $resultat4 = $game4->finish();
    //     $this->assertFalse($resultat4);
    //     $this->assertEquals(false, $resultat4);
    // }

    // /**
    //  * Teast countWinst Bank has Black Jack
    //  * 
    //  * @return void
    //  */
    // public function testCountWinstBankBJ(): void
    // {
        
    //     //cards
    //     $card1 = new CardGraphics();
    //     $card1->set(0, 1);
    //     $card2 = new CardGraphics();
    //     $card2->set(10, 1);
    //     $card3 = new CardGraphics();
    //     $card3->set(6, 2);
    //     $card4 = new CardGraphics();
    //     $card4->set(7, 2);
    //     $card5 = new CardGraphics();
    //     $card5->set(8, 3);
    //     $card5 = new CardGraphics();
    //     $card5->set(4, 0);
    //     //players

    //     //1
    //     $player1 = new Player();
    //     $player1->setName('Jul');
    //     $player1->doBet(2);
    //     $player1->getCard($card2);
    //     $player1->getCard($card3);
    //     $player1->getCard($card4);
    //     $player1->setStatus('fat');
    //     //2
    //     $player2 = new Player();
    //     $player2->setName('Jana');
    //     $player2->doBet(2);
    //     $player2->getCard($card2);
    //     $player2->getCard($card5);
    //     $player2->insure();
    //     $player2->setStatus('ready');
    //     //3
    //     $player3 = new Player();
    //     $player3->setName('Dirk');
    //     $player3->doBet(2);
    //     $player3->getCard($card1);
    //     $player3->getCard($card2);
    //     $player3->setStatus('Black Jack');
    //     //4
    //     $player4 = new Player();
    //     $player4->setName('Per');
    //     $player4->doBet(2);
    //     $player4->getCard($card1);
    //     $player4->getCard($card3);
    //     $player4->setStatus('ready');
    //     //5
    //     $player5 = new Player();
    //     $player5->setName('Björn');
    //     $player5->doBet(2);
    //     $player5->getCard($card1);
    //     $player5->getCard($card4);
       
    //     //6
    //     $player6 = new Player();
    //     $player6->setName('Sten');
    //     $player6->doBet(2);
    //     $player6->getCard($card1);
    //     $player6->getCard($card4);
    //     $player6->insure();

    //     /**game case bank has Black Jack 
    //      * Player 1 Jul profit -2 with fat
    //      * Player 2 Jana profit -1
    //      * Player 3 Dirk profit 0
    //      * Player 4 Per profit -2
    //      * Player 5 Björn profit -2
    //      * Player 6 Sten profit -1
    //      */
    //     $game = new GameInterface();
    //     $game->addPlaying('Jul', $player1);
    //     $game->addPlaying('Jana', $player2);
    //     $game->addPlaying('Dirk', $player3);
    //     $game->addPlaying('Per', $player4);
    //     $game->addPlaying('Björn', $player5);
    //     $game->addPlaying('Sten', $player6);
    //     $game->getBank()->setStatus('Black Jack');
    //     $game->getGame();

    //     $game->countWinst();
    //     $this->assertEquals('loos', $game->findPlayer('Jul')->getStatus());
    //     $this->assertEquals(-2, $game->findPlayer('Jul')->getProfit());
    //     $this->assertEquals(-1, $game->findPlayer('Jana')->getProfit());// insuranse doesnot work !!!!
    //     $this->assertEquals(0, $game->findPlayer('Dirk')->getProfit());
    //     $this->assertEquals(-2, $game->findPlayer('Per')->getProfit());
    //     $this->assertEquals(-2, $game->findPlayer('Björn', )->getProfit());
    //     $this->assertEquals(-1, $game->findPlayer('Sten', )->getProfit());
    // }

    // /**
    //  * Teast countWinst bank status win
    //  * 
    //  * @return void
    //  */
    // public function testCountWinstBAnkWin(): void
    // {
        
    //     //cards
    //     $card1 = new CardGraphics();
    //     $card1->set(0, 1);
    //     $card2 = new CardGraphics();
    //     $card2->set(10, 1);
    //     $card3 = new CardGraphics();
    //     $card3->set(6, 2);
    //     $card4 = new CardGraphics();
    //     $card4->set(7, 2);
    //     $card5 = new CardGraphics();
    //     $card5->set(8, 3);
    //     $card5 = new CardGraphics();
    //     $card5->set(4, 0);
    //     $card6 = new CardGraphics();
    //     $card6->set(3, 0);
    //     //players

    //     //1
    //     $player1 = new Player();
    //     $player1->setName('Jul');
    //     $player1->doBet(2);
    //     $player1->getCard($card2);
    //     $player1->getCard($card3);
    //     $player1->getCard($card4);
    //     $player1->setStatus('fat');
    //     //2
    //     $player2 = new Player();
    //     $player2->setName('Jana');
    //     $player2->doBet(2);
    //     $player2->getCard($card2);
    //     $player2->getCard($card5);
    //     $player2->insure();
    //     $player2->setStatus('ready');
    //     //3
    //     $player3 = new Player();
    //     $player3->setName('Dirk');
    //     $player3->doBet(2);
    //     $player3->getCard($card1);
    //     $player3->getCard($card2);
    //     $player3->setStatus('Black Jack');
    //     //4
    //     $player4 = new Player();
    //     $player4->setName('Per');
    //     $player4->doBet(2);
    //     $player4->getCard($card1);
    //     $player4->getCard($card3);
    //     $player4->setStatus('ready');
    //     //5
    //     $player5 = new Player();
    //     $player5->setName('Björn');
    //     $player5->doBet(2);
    //     $player5->getCard($card1);
    //     $player5->getCard($card4);
    //     //6
    //     $player6 = new Player();
    //     $player6->setName('Sten');
    //     $player6->doBet(2);
    //     $player6->getCard($card1);
    //     $player6->getCard($card4);
    //     $player6->insure();
        
    //     /**game case bank 'win' 
    //      * Player 1 profit -2 with fat
    //      * Player 2 profit -3 with insurance
    //      * Player 3 profit 3
    //      * Player 4 profit -2
    //      * Player 5 profit -2
    //      * Player 6 profit -3 with insurance
    //      */
    //     $game = new GameInterface();
    //     $game->addPlaying('Jul', $player1);
    //     $game->addPlaying('Jana', $player2);
    //     $game->addPlaying('Dirk', $player3);
    //     $game->addPlaying('Per', $player4);
    //     $game->addPlaying('Björn', $player5);
    //     $game->addPlaying('Sten', $player6);
    //     $game->getBank()->setStatus('win');
    //     $game->getGame();

    //     $game->countWinst();
        
    //     $this->assertEquals('loos', $game->findPlayer('Jul')->getStatus());
    //     $this->assertEquals(-2, $game->findPlayer('Jul')->getProfit());
    //     $this->assertEquals(-3, $game->findPlayer('Jana')->getProfit());
    //     $this->assertEquals(3, $game->findPlayer('Dirk')->getProfit());
    //     $this->assertEquals(-2, $game->findPlayer('Per')->getProfit());
    //     $this->assertEquals(-2, $game->findPlayer('Björn', )->getProfit());
    //     $this->assertEquals(-3, $game->findPlayer('Sten', )->getProfit());
    // }

    //     /**
    //  * Teast countWinst bank status Play
    //  * 
    //  * @return void
    //  */
    // public function testCountWinstBankPlaying(): void
    // {
        
    //     //cards
    //     $card1 = new CardGraphics();
    //     $card1->set(0, 1);
    //     $card2 = new CardGraphics();
    //     $card2->set(10, 1);
    //     $card3 = new CardGraphics();
    //     $card3->set(6, 2);
    //     $card4 = new CardGraphics();
    //     $card4->set(7, 2);
    //     $card5 = new CardGraphics();
    //     $card5->set(8, 3);
    //     $card5 = new CardGraphics();
    //     $card5->set(4, 0);
    //     $card6 = new CardGraphics();
    //     $card6->set(1, 0);
    //     $card7 = new CardGraphics();
    //     $card7->set(2, 0);
    //     //players
    //     //1
    //     $player1 = new Player();
    //     $player1->setName('Jul');
    //     $player1->doBet(2);
    //     $player1->getCard($card2);
    //     $player1->getCard($card3);
    //     $player1->getCard($card4);
    //     $player1->setStatus('fat');
    //     //2
    //     $player2 = new Player();
    //     $player2->setName('Jana');
    //     $player2->doBet(2);
    //     $player2->getCard($card2);
    //     $player2->getCard($card5);
    //     $player2->insure();
    //     $player2->setStatus('ready');
    //     //3
    //     $player3 = new Player();
    //     $player3->setName('Dirk');
    //     $player3->doBet(2);
    //     $player3->getCard($card1);
    //     $player3->getCard($card2);
    //     $player3->setStatus('Black Jack');
    //     //4
    //     $player4 = new Player();
    //     $player4->setName('Per');
    //     $player4->doBet(2);
    //     $player4->getCard($card2);
    //     $player4->getCard($card3);
    //     $player4->getCard($card6);
    //     $player4->setStatus('ready');
    //     //5
    //     $player5 = new Player();
    //     $player5->setName('Björn');
    //     $player5->doBet(2);
    //     $player5->getCard($card2);
    //     $player5->getCard($card4);
    //     $player5->getCard($card6);
    //     $player5->setStatus('ready');
    //     //6 with status play
    //     $player6 = new Player();
    //     $player6->setName('Sten');
    //     $player6->doBet(2);
    //     $player6->getCard($card1);
    //     $player6->getCard($card4);
    //     $player6->insure();
        
    //     //7
    //     $player7 = new Player();
    //     $player7->setName('Stan');
    //     $player7->doBet(2);
    //     $player7->getCard($card2);
    //     $player7->getCard($card3);
    //     $player7->getCard($card7);
    //     $player7->insure();
        
    //     /**game case bank 'play' and has 19 poins
    //      * Player 1 Jul profit -2 with fat and 25 points
    //      * Player 2 Jana profit -3 with ready and 15 points and have insuranse
    //      * Player 3 Dirk profit 3 with 21 points
    //      * Player 4 Per profit -2 with 19 points
    //      * Player 5 Björn profit 2 with 20 points
    //      * Player 6 profit -2
    //      * Player 7 profit 2
    //      */
    //     $game = new GameInterface();
    //     $game->addPlaying('Jul', $player1);
    //     $game->addPlaying('Jana', $player2);
    //     $game->addPlaying('Dirk', $player3);
    //     $game->addPlaying('Per', $player4);
    //     $game->addPlaying('Björn', $player5);
    //     $game->addPlaying('Sten', $player6);
    //     $game->addPlaying('Stan', $player7);
    //     $game->getBank()->getCard($card2);
    //     //$game->getBank()->getCard($card4);
    //     $game->getBank()->getCard($card4);

    //     $this->assertEquals(15, $player2->points());

    //     $this->assertEquals(18, $game->getBank()->points());
    //     $game->getGame();

    //     $game->countWinst();
    //     //$this->assertEquals('play', $game->findPlayer('Jana')->getStatus());
    //     $this->assertEquals(-2, $game->findPlayer('Jul')->getProfit());
    //     $this->assertEquals(-3, $game->findPlayer('Jana')->getProfit());
    //     $this->assertEquals(3, $game->findPlayer('Dirk')->getProfit());
    //     $this->assertEquals(2, $game->findPlayer('Per')->getProfit());
    //     $this->assertEquals(2, $game->findPlayer('Björn')->getProfit());
    //     $this->assertEquals(1, $game->findPlayer('Sten')->getProfit());
    //     $this->assertEquals(1, $game->findPlayer('Stan')->getProfit());
        
    // }
    
    // /**
    //  * Teast countWinst bank status fat
    //  * 
    //  * @return void
    //  */
    // public function testCountWinstBankFat(): void
    // {
    //     //cards
    //     $card1 = new CardGraphics();
    //     $card1->set(0, 1);
    //     $card2 = new CardGraphics();
    //     $card2->set(10, 1);
    //     $card3 = new CardGraphics();
    //     $card3->set(6, 2);
    //     $card4 = new CardGraphics();
    //     $card4->set(7, 2);
    //     $card5 = new CardGraphics();
    //     $card5->set(8, 3);
    //     $card5 = new CardGraphics();
    //     $card5->set(4, 0);
    //     $card6 = new CardGraphics();
    //     $card6->set(1, 0);
    //     $card7 = new CardGraphics();
    //     $card7->set(2, 0);
    //     //players
        
    //     //1
    //     $player1 = new Player();
    //     $player1->setName('Jul1');
    //     $player1->doBet(2);
    //     $player1->setStatus('fat');

    //     //1-1
    //     $player11 = new Player();
    //     $player11->setName('Jul11');
    //     $player11->doBet(2);
    //     $player11->getCard($card2);
    //     $player11->getCard($card5);
    //     $player11->getCard($card4);
    //     //2
    //     $player2 = new Player();
    //     $player2->setName('Jana1');
    //     $player2->doBet(2);
    //     $player2->setStatus('ready');
    //     //3
    //     $player3 = new Player();
    //     $player3->setName('Dirk1');
    //     $player3->doBet(2);
    //     $player3->setStatus('Black Jack');
    //     //4
    //     $player4 = new Player();
    //     $player4->setName('Per1');
    //     $player4->doBet(2);
    //     $player4->setStatus('ready');
    //     //5
    //     $player5 = new Player();
    //     $player5->setName('Björn1');
    //     $player5->doBet(2);
    //     //6
    //     $player6 = new Player();
    //     $player6->setName('Sten1');
    //     $player6->doBet(2);
    //     $player6->insure();
        
    //     /**game case bank 'fat' 
    //      * * in array ready[]
    //      * Player 1 Jul1 profit -2
    //      * Player 2 Jana1 profit 2
    //      * Player 3 Dirk1 profit 3 with status "Black Jack"
    //      * Player 4 Per1 profit 2
    //      * * in array playing[]
    //      * Player 5 Björn1 profit 2
    //      * Player 6 Sten1 profit 1 with insuranse
    //      */
    //     $game = new GameInterface();
    //     $game->addPlaying('Jul1', $player1);
    //     $game->addPlaying('Jul11', $player1);
    //     $game->addPlaying('Jana1', $player2);
    //     $game->addPlaying('Dirk1', $player3); // status Black Jack doesnot work
    //     $game->addPlaying('Per1', $player4);
    //     $game->addPlaying('Björn1', $player5);
    //     $game->addPlaying('Sten1', $player6);

    //     //set banksatatus as 'fat'
    //     $game->getBank()->setStatus('fat');

    //     //move players with status 'ready' to the array ready[]
    //     $game->getGame();

    //     $game->countWinst();
        
    //     //look if jana1 will win game iwth direct function winGame
    //     $this->assertEquals(2, $game->findPlayer('Jana1', 'ready')->winGame(1, 1));
    //     $this->assertEquals('loos', $game->findPlayer('Jul1')->getStatus());
    //     $this->assertEquals('win', $game->findPlayer('Dirk1')->getStatus());
    //     $this->assertEquals(0, $game->GetBank()->points());
    //     $this->assertEquals(-2, $game->findPlayer('Jul1')->getProfit());
    //     $this->assertEquals(-2, $game->findPlayer('Jul11')->getProfit());
    //     $this->assertEquals(2, $game->findPlayer('Jana1')->getProfit());
    //     $this->assertEquals(3, $game->findPlayer('Dirk1')->getProfit());
    //     $this->assertEquals(2, $game->findPlayer('Per1')->getProfit());
    //     $this->assertEquals(2, $game->findPlayer('Björn1')->getProfit());
    //     $this->assertEquals(1, $game->findPlayer('Sten1')->getProfit());
    // }
}