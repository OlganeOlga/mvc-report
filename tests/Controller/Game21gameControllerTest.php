<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class Game21gameControllerTest extends WebTestCase
{
    public function testGameInit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/game');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Kortspel 21');
    }
}
