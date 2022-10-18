<?php 

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{   
    public function __construct(EntityManagerInterface $em, AuthorService $authorService) 
    {
        $this->em = $em;
        $this->authorService = $authorService;
    }

    private function createChoicesOfAuthors() {
        $authors = $this->authorService->getAllAuthors();
        $authorsChoices = [];

        foreach($authors as $author) {
            $authorsChoices[$author->getName()] = $author;
        }
        return $authorsChoices;  
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Название книги'])
            ->add('date_release', BirthdayType::class,['label' => 'Дата выхода'])
            // ->add('gerne', null, ['label' => 'Жанр'])
            ->add('imagePath', FileType::class, [
                'label' => 'Фото для вашей книги',
                'required' => false,
            ])
            ->add('author', ChoiceType::class, [
                'label' => 'Выберите автора',
                'choices' => $this->createChoicesOfAuthors()
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}