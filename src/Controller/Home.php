<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\AuthorService;
use App\Service\BookService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Home extends AbstractController 
{   
    // TODO: move doctrine in service
    /**
     * @Route(path="/", name="index")
     */
    public function index(ManagerRegistry $doctrine)
    {   
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findBy([],['id' => 'DESC'], 10);
                   
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
}