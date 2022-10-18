<?php

namespace App\Service;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthorService
{   
    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
    }


    public function getAllAuthors() {
        return $this->em->getRepository(Author::class)->findAll(); 
    }

    public function getAllBooksByAuthor($idAuthor) {
        return $this->em->getRepository(Author::class)->find($idAuthor)->getBooks();
    }
}
