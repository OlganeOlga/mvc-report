<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'create')]
    public function readBook(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Book();
        // $book->setName('Book_name');
        // $book->setIsbn(rand(1000000000000, 9999999999999));

        // tell Doctrine you want to (eventually) save the Book
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $bookid = $book->getId();
        return $this->render('library/create.html.twig', ['bookid' => $bookid]);
    }

    #[Route('/library/create', name: 'book_create', methods: ['POST'])]
    public function createBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();

        $id = $request->request->get('bookid');
        $title = $request->request->get('title');
        $isbn = $request->request->get('isbn');
        $author = $request->request->get('author');
        $cover = $request->request->get('cover');
        $book = $bookRepository
            ->find($id);

        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setTitle($author);
        $book->setIsbn($cover);

        $entityManager->flush();
        
        return $this->render('library/index.html.twig');
    }
}
