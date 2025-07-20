<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
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
     * getId
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getTitle
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * setTitle
     *
     * @param  string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        //If string to long cut it
        (strlen($title) < 255) ? $this->title = $title : $this->title = substr($title, 0, 255);

        return $this;
    }

    /**
     * getISBN
     *
     * @return string|null
     */
    public function getISBN(): ?string
    {
        return $this->isbn;
    }

    /**
     * setISBN
     *
     * Set isbn of book if the param is 13 digits long
     *
     * @param  string|null $isbn
     * @return static
     */
    public function setISBN(?string $isbn): static
    {
        if ($isbn === null) {
            $this->isbn = null;
            return $this;
        }

        //isbn is 13 digits or else null
        (strlen($isbn) == 13) ? $this->isbn = $isbn : $this->isbn = null;

        return $this;
    }

    /**
     * getAuthor
     *
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * setAuthor
     *
     * @param  string|null $author
     * @return static
     */
    public function setAuthor(?string $author): static
    {
        if ($author === null) {
            $this->author = null;
            return $this;
        }

        //If string to long cut it
        (strlen($author) < 255) ? $this->author = $author : $this->author = substr($author, 0, 255);

        return $this;
    }

    /**
     * getImg
     *
     * @return string|null
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * setImg
     *
     * @param string|null $img
     * @return static
     */
    public function setImg($img): static
    {
        if ($img === null) {
            $this->img = null;
            return $this;
        }

        //If string to long null it
        (strlen($img) < 255) ? $this->img = $img : $this->img = null;
        $this->img = $img;

        return $this;
    }
}
