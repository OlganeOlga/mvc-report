<?php
// tests/Controller/MyJsonControllerTest.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookRepository;

class MyJsonNewControllerTest extends WebTestCase
{
    /**
     * Test api/game
     * 
     * @return void
     */
    public function testApiGame(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/game');

        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertJson($client->getResponse()->getContent());
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            'Response is not of type application/json'
        );

        $expKey = ['desk', 'bank', 'player', 'status'];
        $data = json_decode($client->getResponse()->getContent(), true);
        foreach ($expKey as $oneKey) {
            if (array_key_exists($oneKey, $data)) {
                $this->assertTrue(true);
                return;
            }
        }

        // Check the status code
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
        $this->assertArrayHasKey('desk', $content);
        $this->assertArrayHasKey('bank', $content);
        $this->assertArrayHasKey('player', $content);
    }


    /**
     * Test /api/routes
     * 
     * @return void
     */
    public function testJsonLibrary(): void
    {
        $client = static::createClient();
        $client->request('GET', 'api/library/books');

        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertJson($client->getResponse()->getContent());
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            'Response is not of type application/json'
        );

        $expKey = ['desk', 'bank', 'player', 'status'];
        $data = json_decode($client->getResponse()->getContent(), true);
        foreach ($expKey as $oneKey) {
            if (array_key_exists($oneKey, $data)) {
                $this->assertTrue(true);
                return;
            }
        }

        $data = json_decode($client->getResponse()->getContent(), true);
    }

    /**
     * Test api_quote"
     * 
     * @return void
     */
    public function testJsonBookByIsbnBokNotFound(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/library/bookIsbn');

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.flash-warning', 'You enter too few or too many numbers for ISBN!');
    }

    public function testJsonBookByIsbnBokFound(): void
    {
        $client = static::createClient();

        // Define the route and parameters
        $route = '/api/library/bookIsbn';
        $isbn = '9780340960196'; // Example ISBN

        // Simulate a POST request to the specified route with the ISBN parameter
        $client->request('POST', $route, ['isbn' => $isbn]);

        // Get the response
        $response = $client->getResponse();

        // Check if the response is successful
        $this->assertResponseIsSuccessful();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);

        $expKey = ['isbn', 'title', 'isbn', 'bookAuthor', 'cover'];
        $data = json_decode($client->getResponse()->getContent(), true);
        foreach ($expKey as $oneKey) {
            if (array_key_exists($oneKey, $data[0])) {
                $this->assertTrue(true);
            }
        }
    }
}