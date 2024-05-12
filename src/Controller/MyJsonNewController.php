<?php

namespace App\Controller;

use App\Card\Desk;

use ReflectionClass;
use ReflectionMethod;
use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
//use Symfony\Component\HttpKernel\KernelInterface;


use App\Repository\BookRepository; // Import BookRepository

/**
 * Class contains routes displaying information in form of json
 */
class MyJsonNewController extends AbstractController
{
    /**
     * Router displays cardplay21 in the present status
     * 
     * @param SessionInterface $session
     * 
     * @return Response : displays present status of the cardplay 21.
     */
    #[Route('api/game', name: 'json_cardplay21')]
    public function apiGetGameStatus(
        SessionInterface $session
    ): Response {
        $data = [];

        foreach ($session->all() as $key => $value) {
            if($key == "desk" | $key == "bank" | $key == "player") {
                $data[$key] = $value;
            }
        }

        if(count($data) == 0) {
            $data["status"] = "cardplay was not initiated";
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Router displays all books from table 'book' in connected databas as json
     * 
     * @param BookRepository $bookRepository
     * 
     * @return Response : returns all books from table 'book' in connected databas as json.
     */
    #[Route('api/library/books', name: 'json_library')]
    public function jsonLibrary(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();
        return $this->json($books);
    }

    /**
     * Router search and displays all books with given ISBN from table 'book' in connected databas as json
     * 
     * @param BookRepository $bookRepository
     * @param int $isbn 13-digits integer
     * @return Response : returns all books with given ISBN from table 'book' in connected databas as json.
     */
    #[Route('api/library/book/{isbn}', name: 'json_book_by_isbn', methods: ['POST'])]
    public function jsonBookByIsbn(
        BookRepository $bookRepository,
        int $isbn
    ): Response {
        $isbnString = (string) $isbn;
        if(strlen($isbnString) !== 13) {
            $this->addFlash(
                'warning',
                'You enter too few or too many numbers for ISBN!'
            );
            return $this->redirectToRoute('api_landing');
        }
        $book = $bookRepository->findByIsbn($isbn);

        return $this->json($book);
    }
}
