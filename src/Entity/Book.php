<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // @phpstan-ignore-next-line
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 13, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img;

    /**
     * getId.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getTitle.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * setTitle.
     */
    public function setTitle(string $title): static
    {
        // If string to long cut it
        (strlen($title) < 255) ? $this->title = $title : $this->title = substr($title, 0, 255);

        return $this;
    }

    /**
     * getISBN.
     */
    public function getISBN(): ?string
    {
        return $this->isbn;
    }

    /**
     * setISBN.
     *
     * Set isbn of book if the param is 13 digits long
     */
    public function setISBN(?string $isbn): static
    {
        if (null === $isbn) {
            $this->isbn = null;

            return $this;
        }

        // isbn is 13 digits or else null
        (13 == strlen($isbn)) ? $this->isbn = $isbn : $this->isbn = null;

        return $this;
    }

    /**
     * getAuthor.
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * setAuthor.
     */
    public function setAuthor(?string $author): static
    {
        if (null === $author) {
            $this->author = null;

            return $this;
        }

        // If string to long cut it
        (strlen($author) < 255) ? $this->author = $author : $this->author = substr($author, 0, 255);

        return $this;
    }

    /**
     * getImg.
     *
     * @return string|null
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * setImg.
     *
     * @param string|null $img
     */
    public function setImg($img): static
    {
        if (null === $img) {
            $this->img = null;

            return $this;
        }

        // If string to long null it
        (strlen($img) < 255) ? $this->img = $img : $this->img = null;
        $this->img = $img;

        return $this;
    }
}
