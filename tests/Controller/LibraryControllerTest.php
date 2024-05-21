<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Book;
use App\Repository\BookRepository; 
use Doctrine\Persistence\ManagerRegistry;

class LibraryControllerTest extends WebTestCase
{
    // // Set up kernel för scrutiniser
    // protected function setUp(): void
    // {
    //     $kernel = self::bootKernel();
    //     $this->doctrine = $kernel->getContainer()->get('doctrine');
    //     $this->entityManager = $this->doctrine->getManager();
    //     $this->bookRepository = $this->entityManager->getRepository(Book::class);
    // }

    /**
     * Setup for class Mocked BoockRepository
     * 
     * @return BookRepository 
     */
    protected function setMockBookRepo(): BookRepository
    {
        //mock BookRepository
        $mockRepo = $this->createMock(BookRepository::class);

        // Create a sample book entity
        $book = new Book();
        $book->setTitle('Mock Title');
        $book->setAuthor('Mock Author');
        $book->setCover('mock_cover.jpg');
        $book->setIsbn(1234567890321);

        // Configure the mock repository to return the sample book
        $mockRepo->method('find')->willReturn($book);
        return $mockRepo;
    }

    /**
     * Test stertpage for library
     * 
     * @return void
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Bibliotekdatabas startsidan');
    }

    /**
     * Test create book library get-router
     * 
     * @return void
     */
    public function testHandleBookFormGet(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/library/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Skaffa en book');
        $form = $crawler->filter('form');
        $this->assertGreaterThan(0, $form->count(), 'The form was not found in the response');
    }

    public function testCreateBookPost(): void
    {
        $client = static::createClient();

        $formData = [
            'title' => 'Test Book',
            'isbn' => 1234567890123,
            'author' => 'Test Author',
            'cover' => 'test_cover.jpg',
        ];

        $client->request('POST', '/library/create', $formData);
        
        $this->assertTrue($client->getResponse()->isRedirect(), 'Expected response to be a redirect');

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.flash-notice', 'You successfully create a new book with id');

        $bookRepository = $client->getContainer()->get('doctrine')->getRepository(Book::class);
        $book = $bookRepository->findOneBy(['isbn' => 1234567890]);
        $this->assertNotNull($book);
    }

    /**
     * Test fined book in library by id
     * 
     * @return void
     */
    public function testReadChosenBook(): void
    {
        $client = static::createClient();

        $mockRepo = $this->setMockBookRepo();
        // Replace the real BookRepository with the mock in the service container
        self::getContainer()->set('App\Repository\BookRepository', $mockRepo);

        // Perform a POST request to the route
        $client->request('POST', '/library/read/one/', ['bookid' => 1]);

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Assert that the rendered template contains the book details
        $this->assertStringContainsString('Mock Title', $client->getResponse()->getContent());
        $this->assertStringContainsString('Mock Author', $client->getResponse()->getContent());
        $this->assertStringContainsString('mock_cover.jpg', $client->getResponse()->getContent());
        $this->assertStringContainsString('1234567890321', $client->getResponse()->getContent());
    }

    /**
     * Test /library/read/one if book doenot exists
     * 
     * @return void
     */
    public function testReadChosenBookNotFound(): void
    {
        $client = static::createClient();

        // Create a mock BookRepository
        $mockRepo = $this->createMock(BookRepository::class);

        // Configure the mock repository to return null
        $mockRepo->method('find')->willReturn(null);

        // Replace the real BookRepository with the mock in the service container
        self::getContainer()->set('App\Repository\BookRepository', $mockRepo);

        $client->request('POST', '/library/read/one/', ['bookid' => 999]);
        
        // Assert that the response is a redirect
        $this->assertTrue($client->getResponse()->isRedirection());

        // Follow the redirect
        $client->followRedirect();

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Assert that a flash message was added
        $this->assertStringContainsString('No books found for id: 999', $client->getCrawler()->filter('.flash-warning')->text());
    }


    /**
     * Test fined book in library by /library/see/one/{bookId} router
     * 
     * @return void
     */
    public function testReadOneBook(): void
    {
        $client = static::createClient();

        //mock BookRepository
        $mockRepo = $this->setMockBookRepo();

        // Replace the real BookRepository with the mock in the service container
        self::getContainer()->set('App\Repository\BookRepository', $mockRepo);

        // Perform a POST request to the route
        $client->request('GET', '/library/see/one/999');

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Assert that the rendered template contains the book details
        $this->assertStringContainsString('Mock Title', $client->getResponse()->getContent());
        $this->assertStringContainsString('Mock Author', $client->getResponse()->getContent());
        $this->assertStringContainsString('mock_cover.jpg', $client->getResponse()->getContent());
        $this->assertStringContainsString('1234567890321', $client->getResponse()->getContent());
    }

    /**
     * Test /library/read/all 
     * 
     * @return void
     */
    public function testSeeLibrary(): void
    {
        $client = static::createClient();

        $client->request('GET', '/library/read/all');

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('h2', 'Alla böcker i biblioteket');
    }

    /**
     * Test route that uppdate bookinfo
     * 
     * @return void
     */
    public function testUpdateBook(): void
    {
        $client = static::createClient();

        $mockRepo = $this->setMockBookRepo();

        // Replace the real BookRepository with the mock in the service container
        self::getContainer()->set('App\Repository\BookRepository', $mockRepo);

        // Perform a POST request to the route
        $client->request('POST', '/library/update/book', ['bookid' => 19999]);

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Assert that the rendered template contains the book details
        $this->assertStringContainsString('Mock Title', $client->getResponse()->getContent());
        $this->assertStringContainsString('Mock Author', $client->getResponse()->getContent());
        $this->assertStringContainsString('mock_cover.jpg', $client->getResponse()->getContent());
        $this->assertStringContainsString('1234567890321', $client->getResponse()->getContent());
    }

    /**
     * Test route update book if book doesnot exists
     */
    public function testUpdateBookNotFound(): void
    {
        $client = static::createClient();

        $mockRepo = $this->setMockBookRepo();

        // Replace the real BookRepository with the mock in the service container
        self::getContainer()->set('App\Repository\BookRepository', $mockRepo);

        // Perform a POST request to the route
        $client->request('POST', '/library/update/book', ['bookid' => 19999]);

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Assert that the rendered template contains the book details
        $this->assertStringContainsString('Mock Title', $client->getResponse()->getContent());
        $this->assertStringContainsString('Mock Author', $client->getResponse()->getContent());
        $this->assertStringContainsString('mock_cover.jpg', $client->getResponse()->getContent());
        $this->assertStringContainsString('1234567890321', $client->getResponse()->getContent());
    }


    /**
     * Test router library/change/book 
     * 
     * @return void
     */
    private function testChangeBook(): void
    {
        $client = static::createClient();

        // // Mock Request
        // $request = $this->createMock(Request::class);
        // $request->method('request')->willReturnMap([
        //     ['id', 1],
        //     ['title', 'New Title'],
        //     ['author', 'New Author'],
        //     ['cover', 'new_cover.jpg'],
        //     ['isbn', '9876543210987'],
        // ]);

        // Create a sample book entity
        $book = new Book();
        $book->setTitle('Old Title');
        $book->setAuthor('Oldl Author');
        $book->setCover('old_cover.jpg');
        $book->setIsbn(1234567890987);

        // Mock BookRepository
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->method('find')->willReturn($book);

        // Mock EntityManager
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($this->equalTo($book));
        $entityManager->expects($this->once())->method('flush');

        // Replace services in the container with mocks
        self::getContainer()->set('App\Repository\BookRepository', $bookRepository);
        self::getContainer()->set('doctrine.orm.entity_manager', $entityManager);

        // Perform a POST request to the route
        $client->request('POST', '/library/change/book', [
            'id' => 1,
            'title' => 'Updated Title',
            'author' => 'Updated Author',
            'cover' => 'updated_cover.jpg',
            'isbn' => '9876543210987',
        ]);

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Assert the book was updated correctly
        $this->assertEquals('Updated Title', $book->getTitle());
        $this->assertEquals('Updated Author', $book->getAuthor());
        $this->assertEquals('updated_cover.jpg', $book->getCover());
        $this->assertEquals(9876543210987, $book->getIsbn());
        //$this->assertStringContainsString('You successfully uppdated a book with id 1', $client->getCrawler()->filter('.flash-notice')->text());
    }

    /**
     * Test route library/delate GET
     * 
     * @return void
     */
    public function testDeleteBookGet():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'library/delete');

        
        $this->assertResponseIsSuccessful();
        // controll if the title is correct
        $this->assertSelectorTextContains('h2', 'Ta bort en book');
        $form = $crawler->filter('form');
        $this->assertGreaterThan(0, $form->count(), 'The form was not found in the response');
    }

    // /**
    //  * Test delete existing book
    //  */
    // public function testDeleteBookExist(): void
    // {
    //     $client = static::createClient(); 

    //     // Create a sample book entity
    //     $book = $this->createMock(Book::class);
    //     $book->method('getId')->willReturn(1);

    //     // Mock BookRepository
    //     $bookRepository = $this->createMock(BookRepository::class);
    //     $bookRepository->method('find')->with(1)->willReturn($book);

    //     // Mock EntityManager
    //     $entityManager = $this->createMock(EntityManagerInterface::class);
    //     $entityManager->method('getRepository')->willReturn($bookRepository);
    //     $entityManager->method('remove');
    //     $entityManager->method('flush');

    //     // Mock ManagerRegistry
    //     $doctrine = $this->createMock(ManagerRegistry::class);
    //     $doctrine->method('getManager')->willReturn($entityManager);

    //     // Replace services in the container with mocks
    //     self::getContainer()->set('doctrine', $doctrine);

    //     // Perform a POST request to the route
    //     $client->request('POST', '/library/delete', ['id' => 1]);

    //     // Assert the response is a redirect to the app_library route
    //     //$this->assertTrue($client->getResponse()->isRedirection());
        

    //     $this->assertEquals('/library', $client->getResponse()->headers->get('location'));

    //     // Follow the redirect
        

    //     //$this->assertStringContainsString('No book found with id 1', $client->getCrawler()->filter('.flash-warning')->text());
    //     // Assert the flash message was added
    //     $this->assertStringContainsString('You successfully deleted a book with id 1', $client->getCrawler()->filter('.flash-notice')->text());
    // }

    /**
     * Test if varning message uppear f book doesnot exists
     * 
     * @return void
     */
    public function testDeleteBookBookNotExist(): void
    {
        $client = static::createClient();
        //book with id 1 doesnot exists
        $client->request('POST', '/library/delete', ['id' => 1]);

        $this->assertTrue($client->getResponse()->isRedirection());
        
        $this->assertEquals('/library', $client->getResponse()->headers->get('location'));

        // Follow the redirect
        $client->followRedirect();

        $this->assertStringContainsString('No book found with id 1', $client->getCrawler()->filter('.flash-warning')->text());
    }

}
