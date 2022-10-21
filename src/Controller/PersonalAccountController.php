<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonalAccountController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(): Response
    {
        return $this->render('user/profile.html.twig', ['user' => $this->getUser()]);
    }
}
