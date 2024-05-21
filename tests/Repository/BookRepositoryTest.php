<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Persistence\ManagerRegistry;

class BookRepositoryTest extends KernelTestCase
{
    /**
    * @var ManagerRegistry
    */
   private $doctrine;

   /**
    * @var BookRepository
    */
   private $bookRepository;

   /**
    * @var \Doctrine\ORM\EntityManager
    */
   private $entityManager;

   /**
    * Setup for class Book
    */
   protected function setUp(): void
   {
       $kernel = self::bootKernel();
       $this->doctrine = $kernel->getContainer()->get('doctrine');
       $this->entityManager = $this->doctrine->getManager();
       $this->bookRepository = $this->entityManager->getRepository(Book::class);
   }

    /**
     * Test create book
     */
    public function testConstruct(): void
    {
        $book = new Book();
        $this->assertInstanceOf(Book::class, $book);
    }

    /**
     * Test find by isbn
     */
    public function testFindByIsbn(): void
    {
        $isbn = 1234567890;
        $books = $this->bookRepository->findByIsbn($isbn);

        $this->assertIsArray($books);

        foreach ($books as $book) {
            $this->assertInstanceOf(Book::class, $book);
            $this->assertGreaterThanOrEqual($isbn, $book->getIsbn());
        }
    }

}
