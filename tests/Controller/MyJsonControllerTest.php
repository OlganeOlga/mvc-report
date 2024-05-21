<?php
// tests/Controller/MyJsonControllerTest.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyJsonControllerTest extends WebTestCase
{
    public function testApiLanding(): void
    {
        /**
         * Test start page
         */
        $client = static::createClient();
        $client->request('GET', '/api');

        $this->assertResponseIsSuccessful();
        // controll if the title is correct
        $this->assertSelectorTextContains('h2', 'Json Controllers');
    }

    /**
     * Test /api/routes
     */
    public function testApiIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/routes');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('api_routes', $data);
    }

    /**
     * Test api_quote"
     */
    public function testGeQuote(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/quote');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $quote = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('quote', $quote);
    }
}