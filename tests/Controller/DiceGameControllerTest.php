<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Dice\HandDice;
use App\Dice\GraphicDice;

class DiceGameControllerTest extends WebTestCase
{
    /**
     * Test 'pig_start'
     */
    public function testHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game/pig');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Pig game');
    }

    /**
     * Test 'show_roll'
     */
    public function testRoleDice(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game/pig/test/roll');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Roll a dice');
    }

    /**
     * Test 'roll_many'
     */
    public function testRoleDices(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game/pig/test/roll/3');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Roll many dices');
    }

    /**
     * Test 'roll_hand'
     */
    public function testRoleDiceHand(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game/pig/test/dicehand/3');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Roll dice hand');
    }

    /**
     * Test 'start game'
     */
    public function testInit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game/pig/init');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Pig game [START]');
    }

    public function testInitPost(): void
    {
        $client = static::createClient();

        // Make a POST request to the init route
        $client->request('POST', '/game/pig/init');

        $response = $client->getResponse();
        // // check if response is redirected
        // if ($response->isRedirection()) {
        //     $redirectUrl = $response->headers->get('Location');
        //     echo 'Redirect URL: ' . $redirectUrl;
        // } else {
        //     echo 'Response status code: ' . $response->getStatusCode();
        // }

        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Pig game [PLAYING]');
    }

    // /**
    //  * Test 'play game get'
    //  */
    // public function testPlayGet(): void
    // {
    //     Create a client
    //     $client = static::createClient();

    //     Create a mock for the SessionInterface
    //     $session = $this->createMock(SessionInterface::class);

    //     Create a mock for the HandDice class
    //     $die = $this->createMock(GraphicDice::class);
    //     $die->method('roll')->willReturn(1);
    //     $diceHand = new HandDice();
    //     $diceHand->add($die);
    //     $diceHand->method('getString')
    //         ->willReturn(['1', '2', '3']);
    //     // $diceHand->method('roll')
    //     //     ->willReturn([1, 2, 3]);
    //     $diceHand->method('getValues')
    //         ->willReturn([1, 2, 3]);

    //     Set up the session data you expect to use in the controller
    //     $session->method('get')
    //         ->willReturnMap([
    //             ['pig_dicehand', null, $diceHand],
    //             ['pig_dices', null, [1, 2, 3]],
    //             ['pig_round', null, 5],
    //             ['round_total', null, 10],
    //         ]);

    //     Set the mock session into the client's container
    //     $client->getContainer()->set('session', $session);

    //     Make a GET request to the route
    //     $crawler = $client->request('GET', '/game/pig/play');

    //     Assert that the response is successful
    //     $this->assertResponseIsSuccessful();

    //     // Assert that specific data is rendered in the response
    //     $this->assertSelectorTextContains('div#pigDices', '1, 2, 3');
    //     $this->assertSelectorTextContains('div#pigRound', '1');
    //     $this->assertSelectorTextContains('div#pigTotal', '10');
    //     $this->assertSelectorTextContains('div#roundTotal', '5');
    //     $this->assertSelectorTextContains('div#diceValues', '1, 2, 3');


    // /**
    //  * Test roll route method POST
    //  */
    // public function testRollRoutePost()
    // {
    //     // Create a client
    //     $client = static::createClient();

    //     // Create a mock for the SessionInterface
    //     $session = $this->createMock(SessionInterface::class);
    //     $diceHand = $this->createMock(DiceHand::class);

    //     // Set up the session data you expect to use in the controller
    //     $session->method('get')
    //         ->will($this->returnValueMap([
    //             ['pig_dicehand', $diceHand],
    //             ['round_total', 0],
    //         ]));

    //     // Mock the DiceHand behavior
    //     $diceHand->method('roll')->willReturn(null); // Simulate roll method
    //     $diceHand->method('getValues')->willReturn([2, 3, 4]); // Simulate dice roll values

    //     // Set the session in the client's container
    //     $client->getContainer()->set('session', $session);

    //     // Make a POST request to the route
    //     $client->request('POST', '/game/pig/roll');

    //     // Follow the redirect
    //     $client->followRedirect();

    //     // Assert that the response is successful
    //     $this->assertResponseIsSuccessful();

    //     // Optionally, you can check if the flash message was set
    //     $this->assertSelectorTextContains('.flash-warning', 'You got a 1 and you lost the round points!');

    //     // // Verify session data changes
    //     // $this->assertSessionHas($session, 'pig_round', 9); // Since 2 + 3 + 4 = 9
    //     // $this->assertSessionHas($session, 'round_total', 9); // Initially 0 + 9
    // }


    /**
     * Test 'play save post'
     */
    // public function testSave(): void
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('POST', '/game/pig/save');
    //     $response = $client->getResponse();
    //     $crawler = $client->followRedirect();
    //     $this->assertResponseIsSuccessful();

    //     $this->assertSelectorTextContains('h2', 'Pig game [PLAYING]');
    // }

}
