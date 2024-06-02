<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Desk;

class MyJsonCardControllerTest extends WebTestCase
{
    /**
     * Test route api/desk
     * 
     * @return void
     */
    public function testApiDesk(): void
    {
        $client = static::createClient();

        // Simulate a GET request to the /api/desk route
        $client->request('GET', '/api/desk');

        $response = $client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        // Assert the response content type is JSON
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

        
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotNull($responseData);
        $this->assertLessThanOrEqual(52, count($responseData));
    }

    /**
     * Test route api/desk/suffle
     * 
     * @return void
     */
    public function testApiDeskShuffle(): void
    {
        $client = static::createClient();

        // Simulate a GET request to the /api/desk route
        $client->request('POST', '/api/desk/shuffle');

        $response = $client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        // Assert the response content type is JSON
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

        
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotNull($responseData);
        $this->assertEquals(52, count($responseData));
    }

    /**
     * Test route api/desk/draw
     * 
     * @return void
     */
    public function testApiDrawDesk(): void
    {
        $client = static::createClient();

        // Simulate a GET request to the /api/desk route
        $client->request('POST', '/api/desk/draw');

        $response = $client->getResponse();
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        // Assert the response content type is JSON
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

        
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotNull($responseData);
        $this->assertEquals(2, count($responseData));
        $expKey = ['card', 'number'];
        foreach ($expKey as $oneKey) {
            if (array_key_exists($oneKey, $responseData)) {
                $this->assertTrue(true);
                return;
            }
        }
        $this->assertEquals(200, $response->getStatusCode());

        // $content = json_decode($response->getContent(), true);
        // $this->assertIsArray($content);
        // $this->assertArrayHasKey('desk', $content);
        // $this->assertArrayHasKey('bank', $content);
        // $this->assertArrayHasKey('player', $content);
    }

    /**
     * Test route api/desk/draw(num)
     * 
     * @return void
     */

    public function testApiDrawFlerDesk(): void
    {
        $client = static::createClient();
        $session = $this->createMock(SessionInterface::class);
        $desk = new Desk();
        
        $session->set('desk', $desk->getDesk());
        $session->method('get')->willReturn($desk->getDesk());

        $numCard = 3;

        // Log the request URL
        $url = '/api/deck/draw/' . $numCard;
        echo "Request URL: $url\n";
        $client->request('POST', $url);

        $response = $client->getResponse();
        
        // Log the response content
        echo "Response: " . $response->getContent() . "\n";

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        // $expKey = ['drown', 'number'];
        // $data = json_decode($client->getResponse()->getContent(), true);
        // foreach ($expKey as $oneKey) {
        //     if (array_key_exists($oneKey, $data)) {
        //         $this->assertTrue(true);
        //     }
        // }
    }

    /**
     * Test route api/desk/deal/{player}/{num}
     * 
     * @return void
     */
    public function testApiDealCard(): void
    {
        $client = static::createClient();

        // Simulate a GET request to the /api/desk route
        $client->request('POST', 'api/deck/deal/2/3');

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
