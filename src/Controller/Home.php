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
        $book = new Book();
        $book->setTitle('test');
        $book->setDateRelease(new \DateTime());
        $book->setDatePublished(new \DateTime());

        $form = $this->createFormBuilder()
            ->add('title', TextType::class)
            ->add('date_release', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();
        
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findBy(
                [],
                ['id' => 'DESC'], 10);       
        // var_dump($books);die();
        return $this->render('index.html.twig', [
            'books' => $books,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/addNewBook", name="addNewBook")
     */
    public function addNewBook(Request $request, BookService $bookService, BookRepository $bookRepository)
    {   
        $book = $bookService->createNewBookFromRequest($request);
        $bookRepository->add($book);

        return new JsonResponse([
            'result' => 'success',
            'bookID' => $book->getId(),
            'title' => $book->getTitle(),
        ]);
    }
}