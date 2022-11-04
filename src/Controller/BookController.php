<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Service\AuthorService;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends AbstractController
{   
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route(path="/addbook", name="addbook")
     */
    public function addbook(BookService $bookService, Request $request, BookRepository $bookRepository )
    {   
        $form = $this->createForm(BookType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();

            if($image = $form['imagePath']->getData()) {
                $bookName = $form['title']->getData();
                $imagePath = $bookService->moveImage($image, $bookName);
                $book->setImagePath($imagePath);
            }

            $book->setDatePublished(new \DateTime());
            $bookRepository->add($book);
            
            return $this->redirect('/');
        }

        return $this->render('addbook.html.twig',[
                'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/book/api/{id}", name="book/api")
     */
    public function book(BookRepository $bookRepository, $id )
    {   
        if($book = $bookRepository->getBook($id)) {

            $dataOfBook = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'date release' => $book->getDateRelease(),
                'date published' => $book->getDatePublished(),
                'image patch' => $book->getImagePath(),
                'author' => [
                    'id' => $book->getAuthor()->getId()
                ],
                'genre' => $book->getGenre(),
            ];
            
            $response = new Response(json_encode($dataOfBook, JSON_UNESCAPED_UNICODE));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
            // return new JsonResponse($dataOfBook);
        }
        else {
            $error = [
                'error' => 'book is not defined'
            ];

            $response = new Response(json_encode($error, JSON_UNESCAPED_UNICODE));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
    }

}
