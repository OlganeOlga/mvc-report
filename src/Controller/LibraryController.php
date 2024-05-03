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
    #[Route('/library/create', name: 'create_book', methods: ['GET', 'POST'])]
    public function handleBookForm(
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    ): Response {
        $entityManager = $doctrine->getManager();

        // Check if the request is a POST request
        if ($request->isMethod('POST')) {
            // Process the form submission
            // Create a new book object
            $book = new Book();
            $entityManager->persist($book);

            // Get book info from the request
            $title = $request->request->get('title');
            $isbn = $request->request->get('isbn');
            $author = $request->request->get('author');
            $cover = $request->request->get('cover');

            $book->setTitle($title);
            $book->setIsbn(intval($isbn));
            $book->setAuthor($author);
            $book->setCover($cover);

            // Persist changes to the database
            $entityManager->flush();

            return $this->redirectToRoute('app_library');
        }

        // If the request is a GET request, render the form template
        return $this->render('library/create.html.twig');
    }

    // #[Route('/library/create', name: 'create_book_get')]
    // public function readBook(
    //     // ManagerRegistry $doctrine
    // ): Response {
    //     // $entityManager = $doctrine->getManager();

    //     // $book = new Book();

    //     // $entityManager->persist($book);

    //     // $entityManager->flush();

    //     // $bookid = $book->getId();
    //     // return $this->render('library/create.html.twig', ['bookid' => $bookid]);
    //     return $this->render('library/create.html.twig');
    // }

    // #[Route('/library/create', name: 'create_book_post', methods: ['POST'])]
    // public function createBook(
    //     ManagerRegistry $doctrine,
    //     Request $request,
    //     BookRepository $bookRepository
    // ): Response {
    //     $entityManager = $doctrine->getManager();

    //     //crete new book object
    //     $book = new Book();
    //     $entityManager->persist($book);

    //     $entityManager->flush();

    //     // get boks info from request
    //     $title = $request->request->get('title');
    //     $isbn = $request->request->get('isbn');
    //     $author = $request->request->get('author');
    //     $cover = $request->request->get('cover');

    //     $book->setTitle($title);
    //     $book->setIsbn(intval($isbn));
    //     $book->setAuthor($author);
    //     $book->setCover($cover);

    //     $entityManager->persist($book);

    //     // Persist changes to the database
    //     $entityManager->flush();

    //     return $this->redirectToRoute('app_library');
    //     // return $this->render('library/index.html.twig');
    //     //return new Response('Saved new product with id '.$book->getId());
    // }
}
