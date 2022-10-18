<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\AuthorService;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class Home extends AbstractController 
{   

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route(path="/", name="index")
     */
    public function index()
    {   
        $books = $this->bookRepository->getLastTenAddedBooks();

        return $this->render('index.html.twig', [
            'books' => $books,
        ]);
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
     * @Route(path="/authors/{id}", defaults={"id" = null},  name="authors")
     */
    public function authorsAction(Request $request, AuthorService $authorService, $id)
    {   
        $authors = $authorService->getAllAuthors();
        $books = null;

        if($id) {
            $books = $authorService->getAllBooksByAuthor($id);
        }

        if($idAuthor = $request->query->get('id')) {
            $booksByAuthor = $authorService->getAllBooksByAuthor($idAuthor);

            return $this->render('showBooks.html.twig', [
                'books' => $booksByAuthor,
            ]);
        }

        return $this->render('authors.html.twig', [
            'authors' => $authors,
            'books' => $books,
        ]);
    }
}