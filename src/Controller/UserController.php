<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Service\BookService;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{   
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(Request $request, BookService $bookService): Response
    {   
        $user = $this->getUser();
        $favoriteBooks = $user->getFavoriteBooks();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => ['class' => 'd-none']
                ])
            ->add('imagePath', FileType::class, [
                'label' => 'false',
                'required' => false,
                'mapped' => false,
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            if($image = $form['imagePath']->getData()) {
                $username = $form['username']->getData();
                $imagePath = $bookService->moveImage($image, $username);
                $user->setImagePath($imagePath);
            }

            $this->userRepository->add($user);

            return $this->redirectToRoute('profile');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'books' => $favoriteBooks,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/favoriteBookAPI", name="avoriteBookAPI", methods={"POST"})
     */
    public function favoriteBookAPI(Request $request, BookRepository $bookRepository)
    {   
        $user = $this->getUser();
        $bookId = $request->request->get('bookId');
        $favoriteBooks = $user->getFavoriteBooks();

        foreach ($favoriteBooks as $book) {
            if($book->getId() == $bookId) {
                $this->userRepository->removeFavoriteBook($user, $book);

                $response = new Response(json_encode(['result' => 'succeed removed', 'total books in favorites' => count($favoriteBooks)], JSON_UNESCAPED_UNICODE));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        $book = $bookRepository->getBook($bookId);
        $this->userRepository->addFavoriteBook($user, $book);

        $response = new Response(json_encode([
            'result' => 'succeed added',
            'total books in favorites' => count($favoriteBooks)
        ], JSON_UNESCAPED_UNICODE));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
