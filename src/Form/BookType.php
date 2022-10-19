<?php 

namespace App\Form;

use App\Entity\Book;
use App\Repository\GenreRepository;
use App\Service\AuthorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{   
    public function __construct(EntityManagerInterface $em, AuthorService $authorService, GenreRepository $genreRepository) 
    {
        $this->em = $em;
        $this->authorService = $authorService;
        $this->genreRepository = $genreRepository;
    }

    private function createChoicesOfAuthors() {
        $authors = $this->authorService->getAllAuthors();
        $authorsChoices = [];

        foreach($authors as $author) {
            $authorsChoices[$author->getName()] = $author;
        }
        return $authorsChoices;  
    }

    private function createChoicesOfGenres() {
        $genres = $this->genreRepository->getAllGenres();
        $genresChoices = [];

        foreach($genres as $genre) {
            $genresChoices[$genre->getName()] = $genre;
        }
        return $genresChoices;  
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Название книги'])
            ->add('date_release', BirthdayType::class,['label' => 'Дата выхода'])
            ->add('genre', ChoiceType::class, [
                'label' => 'Жанр',
                'choices' => $this->createChoicesOfGenres()
                ])
            ->add('author', ChoiceType::class, [
                'label' => 'Выберите автора',
                'choices' => $this->createChoicesOfAuthors()
            ])   
            ->add('imagePath', FileType::class, [
                'label' => 'Фото для вашей книги',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}