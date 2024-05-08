<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\VarDumper;

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

    #[Route('/library/read/one/', name: 'read_chosen', methods: ['POST'])]
    public function readChosenBook(
        BookRepository $bookRepository,
        Request $request,
    ): Response {
        $id = intval($request->request->get('bookid'));
        $book;
        if($id) {
            $book = $bookRepository->find($id);
        } else {
            throw $this->createNotFoundException(
                'No books found for id '.$id
            );
        }
        $data = [
            'id' => $id,
            'title' => $book->getTitle(),
            'author' => $book->getBookAuthor(),
            'cover' => $book->getCover(),
            'isbn' => $book->getIsbn(),
        ];
        return $this->render('library/read.one.html.twig', $data);
    }

    #[Route('/library/read/all', name: 'see_library', methods: ['GET'])]
    public function seeLibrary(
        BookRepository $bookRepository,
    ): Response {
        $books = $bookRepository->findAll();
        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getBookAuthor(),
                'cover' => $book->getCover(),
                'isbn' => $book->getIsbn(),
            ]; 
        }
        return $this->render('library/read.many.html.twig', ['books' => $data]);
    }

    #[Route('library/update/book', name: 'update_book', methods: ['POST'])]
    public function updateBook(
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    )
    {
        $entityManager = $doctrine->getManager();
        $id = intval($request->request->get('bookid'));
        $data = [];
        try {
            $book = $bookRepository->find($id);
            $data = [
                'id' => $id,
                'title' => $book->getTitle(),
                'author' => $book->getBookAuthor(),
                'cover' => $book->getCover(),
                'isbn' => $book->getIsbn(),
            ];
        } catch (Exception $e) {
            throw $this->createNotFoundException(
                'No books found for id '.$id
            );
        }

        return $this->render('library/update.html.twig', $data);
    }

    #[Route('library/cnahge/book', name: 'change_the_book', methods: ['POST'])]
    public function changeBook(
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    )
    {
        $entityManager = $doctrine->getManager();
        $id = intval($request->request->get('id'));
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $cover = $request->request->get('cover');
        $isbn = $request->request->get('isbn');
        $book = $bookRepository->find($id);

        $book->setTitle($title);
        $book->setIsbn(intval($isbn));
        $book->setAuthor($author);
        $book->setCover($cover);

            
        $entityManager->persist($book);


        // Persist changes to the database
        $entityManager->flush();

        return $this->redirectToRoute('app_library');
    }

    #[Route('library/delete', name: 'delete_book', methods: ['GET', 'POST'])]
    public function deleteBook(
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    ) {
        if($request->isMethod('POST')) {
            $id = intval($request->request->get('id'));
            $entityManager = $doctrine->getManager();
            $book = $entityManager->getRepository(Book::class)->find($id);

            if (!$book) {
                throw $this->createNotFoundException(
                    'No book found for id '.$id
                );
            }

            $entityManager->remove($book);
            $entityManager->flush();


            return $this->redirectToRoute('app_library');

        }
        return $this->render('library/delete.html.twig');
    }
}

