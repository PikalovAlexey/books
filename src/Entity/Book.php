<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_release;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"}))
     */
    private $date_published = 'CURRENT_TIMESTAMP';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagePath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDateRelease(): ?\DateTimeInterface
    {
        return $this->date_release;
    }

    public function setDateRelease(\DateTimeInterface $date_release): self
    {
        $this->date_release = $date_release;

        return $this;
    }

    public function getDatePublished()
    {
        return $this->date_published;
    }

    public function setDatePublished(\DateTimeInterface $date_published): self
    {
        $this->date_published = $date_published;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }
}
