<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request; // Import Request class
use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository; // Import BookRepository

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'create_book_get')]
    public function readBook(
        // ManagerRegistry $doctrine
    ): Response {
        // $entityManager = $doctrine->getManager();

        // $book = new Book();

        // $entityManager->persist($book);

        // $entityManager->flush();

        // $bookid = $book->getId();
        // return $this->render('library/create.html.twig', ['bookid' => $bookid]);
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create1', name: 'library_create')]
    public function createBook1(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle('Dune');
        $book->setAuthor('Frank Herbert');
        $book->setIsbn(9780340960196);
        $book->setCover('https://s1.adlibris.com/images/6730818/dune.jpg');

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new book with id '.$book->getId());
    }

    #[Route('/library/create', name: 'create_book_post', methods: ['POST'])]
    public function createBook(
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Book();

        //$bookid = $book->getId();

        // Update book properties with form data
        $book->setTitle($request->request->get('title'));
        $book->setIsbn($request->request->get('isbn'));
        $book->setAuthor($request->request->get('author'));
        $book->setCover($request->request->get('cover'));

        // Persist changes to the database
        $entityManager->flush();

        return $this->redirectToRoute('app_library');
        // return $this->render('library/index.html.twig');
        //return new Response('Saved new product with id '.$book->getId());
    }
}
