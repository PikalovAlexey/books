<?php

namespace App\Service;

use App\Entity\Book;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

class BookService
{   
    private $params;
    private $defaultImagePath;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->defaultImagePath = 'img/book-min.png';
    }

    private function moveImage($image, $bookName)
    {
        $imageName = str_replace(" ", "-", $bookName).'.'.$image->getClientOriginalExtension();
        $image->move(
            $this->params->get('kernel.project_dir').'/public/img',
            $imageName
        );

        $imagePath = 'img/'.$imageName;

        return $imagePath;
    }

    public function createNewBookFromRequest(Request $request): Book
    {
        // TODO: To release added author and gernes
        // $authorID = $request->query->get('authorID');
        // $gernesID = $request->query->get('gernesID');

        $book = new Book();
        $book->setTitle($request->request->get('title'));
        $book->setDateRelease(new \DateTime($request->request->get('date_release')));
        $book->setDatePublished(new \DateTime());

        if($image = $request->files->get('photo')) {
            $imagePath = $this->moveImage($image,$book->getTitle());
            $book->setImagePath($imagePath);
        }
        else {
            $book->setImagePath($this->defaultImagePath);
        }

        return $book;
    }
}
