<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository; 

class LibraryControllerTest extends WebTestCase
{
    /**
     * Test stertpage for library
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Bibliotekdatabas startsidan');
    }

    /**
     * Test create book library
     */
    public function testHandleBookFormGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Skaffa en book');
    }

    // /**
    //  * Test crete book POST
    //  */
    // public function testCreateBookPost(): void
    // {
    //     // Create a client
    //     $client = static::createClient();

    //     // Define the form data
    //     $formData = [
    //         'title' => 'Test Book',
    //         'isbn' => 1234567890,
    //         'author' => 'Test Author',
    //         'cover' => 'test_cover.jpg',
    //     ];

    //     // Make a POST request to the route with the form data
    //     $client->request('POST', '/library/create', $formData);

    //     // Follow the redirect
    //     $client->followRedirect();

    //     // Assert that the response is successful
    //     $this->assertResponseIsSuccessful();

    //     // Assert that the flash message is present
    //     $this->assertSelectorTextContains('.flash-notice', 'You successfully create a new book with id');

    //     // Optionally, assert that the new book was created in the database
    //     $bookRepository = $client->getContainer()->get('doctrine')->getRepository(Book::class);
    //     $book = $bookRepository->findByIsbn(1234567890);
    //     $this->assertNotNull($book);
    //     //$this->assertEquals('Test Author', $book->getAuthor());
    // }

    public function testCreateBookPost(): void
{
    $client = static::createClient();

    $formData = [
        'title' => 'Test Book',
        'isbn' => 1234567890,
        'author' => 'Test Author',
        'cover' => 'test_cover.jpg',
    ];

    $client->request('POST', '/library/create', $formData);

    if (!$client->getResponse()->isRedirect()) {
        echo $client->getResponse()->getContent(); // Output the response content for debugging
    }
    
    $this->assertTrue($client->getResponse()->isRedirect(), 'Expected response to be a redirect');

    $client->followRedirect();

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('.flash-notice', 'You successfully create a new book with id');

    $bookRepository = $client->getContainer()->get('doctrine')->getRepository(Book::class);
    $book = $bookRepository->findOneBy(['isbn' => 1234567890]);
    $this->assertNotNull($book);
    //$this->assertEquals('Test Author', $book->getAuthor());
}

    // /**
    //  * Test see book in library
    //  */
    // public function testReadChosenBook(): void
    // {
    //     $client = static::createClient();
    //     $client->request('POST', '/library/read/one/');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h2', 'Bibliotekdatabas startsidan');
    // }
}
