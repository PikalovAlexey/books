<?php

namespace App\Service;

// use App\Entity\Book;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

class BookService
{   
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function moveImage($image, $bookName)
    {
        $imageName = str_replace(" ", "-", $bookName).'.'.$image->getClientOriginalExtension();
        $image->move(
            $this->params->get('kernel.project_dir').'/public/img',
            $imageName
        );

        $imagePath = 'img/'.$imageName;

        return $imagePath;
    }
}
