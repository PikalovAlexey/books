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
     * @Route(path="/addAuthor", name="addauthor")
     */
    public function addAuthor(Request $request, AuthorRepository $authorRepository)
    {   
        $form = $this->createForm(AuthorType::class);

        $form->handleRequest($request); 
    
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $authorRepository->add($author);
            
            return $this->redirect('/');
        }
        return $this->render('formAddAuthor.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route(path="/authors/{id}", defaults={"id" = null},  name="authors")
     */
    public function authorsAction(Request $request, AuthorService $authorService, AuthorRepository $authorRepository, $id)
    {   
        $authors = $authorService->getAllAuthors();
        $form = $this->createForm(AuthorType::class);
        $books = null;

        $form->handleRequest($request); 
    
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $authorRepository->add($author);
            
            return $this->redirect('/authors');
        }

        if(is_numeric($id)) {
            $books = $authorService->getAllBooksByAuthor($id);
        }

        if($idAuthor = $request->query->get('id')) {
            $books = $authorService->getAllBooksByAuthor($idAuthor);
            if(count($books) == 0) {
                return new JsonResponse(false);
            }
            return $this->render('showBooks.html.twig', [
                'books' => $books,
            ]);
        }

        return $this->render('authors.html.twig', [
            'authors' => $authors,
            'books' => $books,
            'form' => $form->createView(),
        ]);
    }
}