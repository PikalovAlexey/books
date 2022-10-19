<?php

namespace App\Controller;

use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GenreController extends AbstractController 
{   

    public function __construct(GenreRepository $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    /**
     * @Route(path="/genres/{id}", defaults={"id" = null}, name="genres")
     */
    public function genresAction(Request $request, $id)
    {   
        $genres = $this->genreRepository->getAllGenres();
        $form = $this->createForm(GenreType::class);
        $books = null;

        $form->handleRequest($request); 
    
        if ($form->isSubmitted() && $form->isValid()) {
            $genre = $form->getData();
            $this->genreRepository->add($genre);
            
            return $this->redirect('/genres');
        }

        if($id) {
            $books = $this->genreRepository->getAllBooksByGenre($id);
        }

        if($idGenre = $request->query->get('id')) {
            $books = $this->genreRepository->getAllBooksByGenre($idGenre);

            if(count($books) == 0) {
                return new JsonResponse(false);
            }
            else {
                return $this->render('showBooks.html.twig', [
                    'books' => $books,
                ]);
            }
        }

        return $this->render('genres.html.twig', [
            'genres' => $genres,
            'books' => $books,
            'form' => $form->createView()
        ]);
    }
}