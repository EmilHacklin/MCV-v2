<?php

namespace App\Repository;

use Exception;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * returnBook
     *
     * Return book object (works like a constructor)
     *
     * @param  string $title
     * @param  ?string $isbn
     * @param  ?string $author
     * @param  ?string $img
     * @return Book
     */
    public function returnBook(string $title, ?string $isbn = null, ?string $author = null, ?string $img = null): Book
    {
        $book = new Book();

        $book->setTitle($title);
        $book->setISBN($isbn);
        $book->setAuthor($author);
        $book->setImg($img);

        return $book;
    }

    /**
     * readAllBooks
     *
     * Reads all books from the database and returns a array of books
     *
     * @return array<string, array<int, array<string, int|string|null>>>
     */
    public function readAllBooks(): array
    {
        $data = [
                "books" => [],
        ];

        $books = $this->findAll();

        for ($i = 0; $i < Count($books); $i++) {
            $data["books"][$i]["id"] = $books[$i]->getId();
            $data["books"][$i]["title"] = $books[$i]->getTitle();
            $data["books"][$i]["isbn"] = $books[$i]->getISBN();
            $data["books"][$i]["author"] = $books[$i]->getAuthor();
            $data["books"][$i]["img"] = $books[$i]->getImg();
        }

        return $data;
    }

    /**
     * readOneBook
     *
     * @param  int $id
     * @return  array<string, array<int|string, array<string, int|string|null>|int|string|null>>
     */
    public function readOneBook(int $id): array
    {
        $data = [
                "book" => [
                    'id' => $id,
                    'title' => '',
                    'isbn' => null,
                    'author' => null,
                    'img' => null
                ],
        ];

        // If id is less then 0
        if ($id < 0) {
            return $data;
        }

        $book = $this->findOneBy(['id' => $id]);

        // If no book is found
        if ($book === null) {
            return $data;
        }

        $data["book"]["title"] = $book->getTitle();
        $data["book"]["isbn"] = $book->getISBN();
        $data["book"]["author"] = $book->getAuthor();
        $data["book"]["img"] = $book->getImg();

        return $data;
    }

    /**
     * readOneBookISBN
     *
     * @param  string $isbn
     * @return  array<string, array<int|string, array<string, int|string|null>|int|string|null>>
     */
    public function readOneBookISBN(string $isbn): array
    {
        $data = [
                "book" => [
                    'id' => 0,
                    'title' => '',
                    'isbn' => $isbn,
                    'author' => null,
                    'img' => null
                ],
        ];

        //isbn is not 13 digits return the empty data
        if (strlen($isbn) != 13) {
            return $data ;
        }

        $book = $this->findOneBy(['isbn' => $isbn]);

        // If no book is found
        if ($book === null) {
            return $data;
        }

        $data["book"]["id"] = $book->getId();
        $data["book"]["title"] = $book->getTitle();
        $data["book"]["author"] = $book->getAuthor();
        $data["book"]["img"] = $book->getImg();

        return $data;
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
