<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $publication_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ISBN;

    /**
     * @ORM\Column(type="integer")
     */
    private $pages_number;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Author")
     * @ORM\JoinTable(name="books_authors")
     */
    private $booksAuthors;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->booksAuthors = new ArrayCollection();
    }


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

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function setPublicationDate(\DateTimeInterface $publication_date): self
    {
        $this->publication_date = $publication_date;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getPagesNumber(): ?int
    {
        return $this->pages_number;
    }

    public function setPagesNumber(int $pages_number): self
    {
        $this->pages_number = $pages_number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getBooksAuthors()
    {
        return $this->booksAuthors;
    }

    /**
     * @param mixed $booksAuthors
     */
    public function addBooksAuthors($authors): void
    {
        foreach ($authors as $author)
        {
            if($this->booksAuthors->contains($author)) {
                continue;
            }
            $this->booksAuthors->add($author);
        }
    }

    /**
     * @param mixed $booksAuthors
     */
    public function removeBooksAuthors(Author $author): void
    {
        $this->booksAuthors->removeElement($author);
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }





}
