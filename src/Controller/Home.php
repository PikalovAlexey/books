<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\BookService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Home extends AbstractController 
{   
    /**
     * @Route(path="/", name="index")
     */
    public function index(ManagerRegistry $doctrine)
    {   
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findBy(
                [],
                ['id' => 'DESC'], 10);       
        // var_dump($books);die();
        return $this->render('index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route(path="/addbook", name="addbook")
     */
    public function addbook(Request $request, BookService $bookService)
    {   
        return $this->render('addNewBook.html.twig');
    }

    /**
     * @Route(path="/addNewBookAPI", name="addNewBookAPI")
     */
    public function addNewBook(Request $request, BookService $bookService, BookRepository $bookRepository)
    {   
        $book = $bookService->createNewBookFromRequest($request);
        $bookRepository->add($book);

        return new JsonResponse([
            'result' => 'success',
            'bookID' => $book->getId(),
            'bookName' => $book->getTitle(),
        ]);
    }
}