<?php

namespace App\Service;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;

class BookService
{
    public function createNewBookFromRequest(Request $request): Book
    {
        // TODO: To release added author and gernes
        // $authorID = $request->query->get('authorID');
        // $gernesID = $request->query->get('gernesID');

        $book = new Book();
        $book->setTitle($request->query->get('title'));
        $book->setDateRelease(new \DateTime($request->query->get('dateRelease')));
        $book->setDatePublished(new \DateTime());
        
        return $book;
    }
}
