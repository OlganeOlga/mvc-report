<?php

namespace App\Tests;

use App\BlackJack\CardGraphics;
use App\BlackJack\Player;
use App\BlackJack\Bank;
use App\BlackJack\WinstCounter;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectControllerTest extends WebTestCase
{
    /**
     * Test projekt homepage
     * @return void
     */
    public function testProject(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/proj');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'MVC projekt: kortspel');
    }

    /**
     * Test projekt about page
     * @return void
     */
    public function testAboutProject(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/proj/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Om MVC projekt');
    }

    /**
     * Test page that shows random card
     * @return void
     */
    public function testGetOneCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/oneCard');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'En kort');
    }

    /**
     * Test page that start play: shows desk and ask about amount of players
     * @return void
     */
    public function testInitiatePlay(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/init');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Play Black Jack');
        $this->assertSelectorTextContains('p', 'Projektet implementerar kortspel Black Jack');
    }

    /**
     * Test router thet get amount of players and their names 
     * create players and add thei to the gaim 
     * It also creates banks bet
     * and redirect to formulär
     * where players can set their bet 
     * 
     * 
     * @return void
     */
    public function testGetPlayers(): void
    {
        $client = static::createClient();
        $client->request('POST', '/proj/getPlayers', ['number' => 2, 'player1' => 'Mia', 'player2' => 'Jon']);
        $this->assertTrue($client->getResponse()->isRedirection());
        $this->assertResponseRedirects('/proj/satsa');
        $client->followRedirect();
        
        $responseData = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertSelectorTextContains('h2', 'Satsa peng. Bankens sats är');
    }

    // /**
    //  * Test page that shows players and their hands, points, status
    //  * action possibilities,
    //  * 
    //  * @return void
    //  */
    // public function testTaPlayersSats(): void
    // {
    //     $client = static::createClient();

    //     // Create a mock for the SessionInterface
    //     $session = $this->createMock(SessionInterface::class);

    //     // Create the game object and players
    //     $game = new Game();
    //     $names = ['Mia'];

    //     // Configure the mock to return the appropriate values
    //     $session->method('get')
    //             ->will($this->returnValueMap([
    //                 ['game', null, $game],
    //                 ['names', null, $names]
    //             ]));

    //     // Inject the mock session into the client's container
    //     $client->getContainer()->set(SessionInterface::class, $session);

    //     // Simulate form data for the POST request
    //     $playerSats = ['Mia' => 10];

    //     // Make the POST request
    //     $client->request('POST', '/proj/playPost', $playerSats);

    //     // Check if the response redirects to the expected route
    //     $this->assertResponseRedirects('/proj/playView');

    //     // Follow the redirect
    //     $client->followRedirect();

    //     // Check the final response status code
    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());
    // }

    // /**
    //  * Test router thet get amount of players and their names 
    //  * create players and add thei to the gaim 
    //  * It also creates banks bet
    //  * and redirect to formulär
    //  * where players can set their bet 
    //  * 
    //  * 
    //  * @return void
    //  */
    // public function testGetPlayers(): void
    // {
    //     $client = static::createClient();
    //     $client->request('POST', '/proj/getPlayers', [$names => ['Mia'], $name => 10]);
    //     $this->assertTrue($client->getResponse()->isRedirection());
    //     $client->followRedirect();
        
    //     $responseData = json_decode($client->getResponse()->getContent(), true);
        
        
    //     // $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h2', 'Satsa peng. Bankens sats är');
    // }


    // /**
    //  * Test page that shows game
    //  * @return void
    //  */
    // public function testGameFinish(): void
    // {
    //     $client = static::createClient();
    //     $card1 = new CardGraphics();
    //     $card1->set(0, 1);
    //     $card2 = new CardGraphics();
    //     $card2->set(10, 1);
    //     $card3 = new CardGraphics();
    //     $card3->set(6, 2);
    //     $card4 = new CardGraphics();
    //     $card4->set(7, 2);

    //     $player4 = new Player();
    //     $player4->setName('Per');
    //     $player4->doBet(2);
    //     $player4->getCard($card1);
    //     $player4->getCard($card3);
    //     $player4->setStatus('ready');

    //     $game = new Game();
    //     $game->addPlaying('Per', $player4);
        
    //     $bank = $this->createMock(Bank::class);
    //     $bank->method('points')->willReturn(19);
    //     $bank->method('blackJack')->willReturn(false);
    //     $bank->method('getStatus')->willReturn('play');
    //     $game->setBank($bank);

    //     $game->countWinst();
    //     $session = $this->createMock(SessionInterface::class);
    //     $session->method('get')->willReturn($game);
    //     $client->request('GET', '/proj/finish');
    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h2', 'Du spelar Black Jack.');
    // }


}
