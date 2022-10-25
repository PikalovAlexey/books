<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\BookService;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
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
            'form' => $form->createView()
        ]);
    }
}
