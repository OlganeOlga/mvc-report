<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
/**
 * Test CardPlayController
 */
class CardPlayControllerTest extends WebTestCase
{
    /**
     * Test Route 'debug_session
     */
    public function testSession(): void
    {
        $client = static::createClient();
        $client->request('GET', '/session');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Debug kortspel, visa session');
    }

    /**
     * Test Route 'delete_session
     */
    public function testDelate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/session/delete');

        $this->assertResponseIsSuccessful();
        // Check if the flash message is set
        $data = json_decode($client->getResponse()->getContent(), true);
             
        // Assert data is empty
        $this->assertEmpty($data);
    }

    /**
     * Test Route 'card_play'
     */
    public function testCardPlay(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Fransk-engelsk kortspel');
        $data = json_decode($client->getResponse()->getContent(), true);
             
        // Assert data is empty
        $this->assertEmpty($data);
        $this->assertNull($data);
    }

    /**
     * Test Route 'one_card'
     */
    public function testOneCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/desk/test/card');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Här ser du kortet.');
    }

    /**
     * Test Route 'desk of cards'
     */
    public function testDeskCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/desk');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Se kortspel');
    }

    /**
     * Test Route 'shuffle card'
     */
    public function testShuffleCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/suffle');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Test Route 'draw_card'
    */
    public function testDrawCard(): void
    {
        $client = static::createClient();
        $client->request('GET', 'card/desk/draw');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Dra ett kort');
    }

    // /**
    //  * Test Route 'draw_seweral'
    // */
    // public function testDrawSomeCard(): void
    // {
    //     $session = $this->createMock(SessionInterface::class);
    //     $desk = [[1,1], [3,3], [2,2], [3,0]]; // Example desk data
    //     $session->expects($this->any())
    //         ->method('get')
    //         ->willReturnMap([
    //             ['desk', $desk],
    //             ['cards', []] // Assuming cards are initially empty
    //         ]);
    //     $client = static::createClient();
    //     $client->request('GET', '/card/desk/draw/1');

    //     //$this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h2', 'Du drå 1 kort!');
    // }

    /**
     * Test Route 'deal_card'
    */
    public function testDealCard(): void
    {
        $client = static::createClient();
        $client->request('GET', 'card/deck/deal/2/2');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Kort delas ut till 2 spelare!');
    }
}

