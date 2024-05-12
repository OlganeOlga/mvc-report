<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\VarDumper;
use InvalidArgumentException;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository; // Import BookRepository

/**
 * Class contains routes for library API
 * let works with the table Book in the databas
 */
class LibraryController extends AbstractController
{
    /**
     * Start route for the Library
     * 
     * @return Response
     */
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    /**
     * Add neuw book to the library table
     * 
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * 
     * @return Response
     */    
    #[Route('/library/create', name: 'create_book', methods: ['GET', 'POST'])]
    public function handleBookForm(
        ManagerRegistry $doctrine,
        Request $request
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
            $this->addFlash(
                "notice",
                "You successfully create a new book with id {$book->getId()}"
            );

            return $this->redirectToRoute('app_library');
        }

        // If the request is a GET request, render the form template
        return $this->render('library/create.html.twig');
    }

    /**
     * Display ont book from the library table
     * with given ID
     * 
     * @param BookRepository $bookRepository,
     * @param Request $request
     * 
     * @return Response
     */    
    #[Route('/library/read/one/', name: 'read_chosen', methods: ['POST'])]
    public function readChosenBook(
        BookRepository $bookRepository,
        Request $request,
    ): Response {
        $bookId = intval($request->request->get('bookid'));
        try {
            $book = $bookRepository->find($bookId);
            $data = [
                'id' => $bookId,
                'title' => $book->getTitle(),
                'author' => $book->getBookAuthor(),
                'cover' => $book->getCover(),
                'isbn' => $book->getIsbn(),
            ];
            return $this->render('library/read.one.html.twig', $data);
        } catch (InvalidArgumentException $e) {
            $this->addFlash(
                "warning",
                "No books found for id: {$bookId}"
            );
            return $this->redirectToRoute('app_library');
        }
    }

     /**
     * Display ont book in the library table
     * 
     * @param BookRepository $bookRepository,
     * @param int $bookId - id of book-row in the table
     * @return Response
     */ 
    #[Route('/library/see/one/{bookId}', name: 'see_one', methods: ['GET'])]
    public function readOneBook(
        BookRepository $bookRepository,
        int $bookId
    ): Response {
        $book = $bookRepository->find($bookId);
        $data = [
            'id' => $bookId,
            'title' => $book->getTitle(),
            'author' => $book->getBookAuthor(),
            'cover' => $book->getCover(),
            'isbn' => $book->getIsbn(),
        ];
        return $this->render('library/read.one.html.twig', $data);
    }

    /**
     * Display all books in the library table
     *
     * @param BookRepository $bookRepository,
     *
     * @return Response
     */ 
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

    /**
     * Get bookId and show information about a book in the
     * form, where you can change info and update the informatioin about the book
     * book to the library table
     * 
     * @param BookRepository $bookRepository,
     * @param Request $request,
     * @param ManagerRegistry $doctrine,
     * @return Response
     */ 
    #[Route('library/update/book', name: 'update_book', methods: ['POST'])]
    public function updateBook(
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    ): Response {
        //$entityManager = $doctrine->getManager(); comment away this line as it is seen as anused by phpmd
        $bookId = intval($request->request->get('bookid'));
        $data = [];
        try {
            $book = $bookRepository->find($bookId);
            $data = [
                'id' => $bookId,
                'title' => $book->getTitle(),
                'author' => $book->getBookAuthor(),
                'cover' => $book->getCover(),
                'isbn' => $book->getIsbn(),
            ];

            return $this->render('library/update.html.twig', $data);
        } catch (InvalidArgumentException $e) {
            $this->addFlash(
                "warning",
                "No books found for id: {$bookId}"
            );
            return $this->redirectToRoute('app_library');
        }
    }

    /**
     * Change information about a book in the library table
     * 
     * @param BookRepository $bookRepository,
     * @param Request $request,
     * @param ManagerRegistry $doctrine,
     * @return Response
     */ 
    #[Route('library/cnahge/book', name: 'change_the_book', methods: ['POST'])]
    public function changeBook(
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    ): Response {
        $entityManager = $doctrine->getManager();
        $bookId = intval($request->request->get('id'));
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $cover = $request->request->get('cover');
        $isbn = $request->request->get('isbn');
        $book = $bookRepository->find($bookId);

        $book->setTitle($title);
        $book->setIsbn(intval($isbn));
        $book->setAuthor($author);
        $book->setCover($cover);

        $entityManager->persist($book);

        // Persist changes to the database
        $entityManager->flush();

        $this->addFlash(
            "notice",
            "You successfully uppdated a book with id {$bookId}"
        );
        return $this->redirectToRoute('app_library');
    }

    /**
     * Add remove a book-row from the library table
     *
     * @param Request $request,
     * @param ManagerRegistry $doctrine,
     * @return Response
     */ 
    #[Route('library/delete', name: 'delete_book', methods: ['GET', 'POST'])]
    public function deleteBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        if($request->isMethod('POST')) {
            $bookId = intval($request->request->get('id'));
            $entityManager = $doctrine->getManager();
            $book = $entityManager->getRepository(Book::class)->find($bookId);

            if (!$book) {
                $this->addFlash(
                    "warning",
                    "No book found with id {$bookId}"
                );
                return $this->redirectToRoute('app_library');
            }

            $entityManager->remove($book);
            $entityManager->flush();

            $this->addFlash(
                "notice",
                "You successfully deleted a book with id {$bookId}"
            );

            return $this->redirectToRoute('app_library');

        }
        return $this->render('library/delete.html.twig');
    }
}
