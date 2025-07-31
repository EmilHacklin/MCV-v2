<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    private ManagerRegistry $doctrine;

    /**
     * __construct.
     *
     * @return void
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;

        parent::__construct($doctrine, Book::class);
    }

    /**
     * returnBook.
     *
     * Return book object (works like a pseudo constructor)
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
     * saveBook.
     *
     * Saves a book to the database
     */
    public function saveBook(string $title, ?string $isbn = null, ?string $author = null, ?string $img = null): void
    {
        // If title is empty
        if ('' === $title) {
            return;
        }

        $book = $this->returnBook($title, $isbn, $author, $img);

        $entityManager = $this->doctrine->getManager();

        // tell Doctrine you want to (eventually) save the Book
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
    }

    /**
     * updateBook.
     *
     * Saves a book to the database
     */
    public function updateBook(int $id, string $title, ?string $isbn = null, ?string $author = null, ?string $img = null): void
    {
        $book = $this->findOneBy(['id' => $id]);

        // If the book can't be found
        if (null === $book) {
            return;
        }

        // If title is empty
        if ('' === $title) {
            return;
        }

        $book->setTitle($title);

        // If you have new ISBN
        if (null !== $isbn) {
            $book->setIsbn($isbn);
        }

        // If you have new Author
        if (null !== $author) {
            $book->setAuthor($author);
        }

        // If you have new image
        if (null !== $img) {
            $book->setImg($img);
        }

        // Get the entity manager
        $entityManager = $this->doctrine->getManager();

        // Persist is optional for existing entities, but it doesn't hurt
        $entityManager->persist($book);

        // Flush to save changes
        $entityManager->flush();
    }

    /**
     * deleteBook.
     */
    public function deleteBook(int $id): void
    {
        $book = $this->findOneBy(['id' => $id]);

        // If the book can't be found
        if (null === $book) {
            return;
        }

        // Get the entity manager
        $entityManager = $this->doctrine->getManager();

        // Remove the book
        $entityManager->remove($book);
        $entityManager->flush();
    }

    /**
     * readAllBooks.
     *
     * Reads all books from the database and returns an array of books.
     *
     * @return array
     *               An associative array with a key 'books' containing an array of book details:
     *               - books: array<array{
     *               id: int|null,
     *               title: string|null,
     *               isbn: string|null,
     *               author: string|null,
     *               img: string|null
     *               }>
     *
     * @phpstan-return array{
     *   books: array<array{
     *     id: int|null,
     *     title: string|null,
     *     isbn: string|null,
     *     author: string|null,
     *     img: string|null
     *   }>
     * }
     */
    public function readAllBooks(): array
    {
        $data = [
            'books' => [],
        ];

        $books = $this->findAll();

        $numBooks = count($books);

        for ($i = 0; $i < $numBooks; ++$i) {
            $data['books'][$i]['id'] = $books[$i]->getId();
            $data['books'][$i]['title'] = $books[$i]->getTitle();
            $data['books'][$i]['isbn'] = $books[$i]->getISBN();
            $data['books'][$i]['author'] = $books[$i]->getAuthor();
            $data['books'][$i]['img'] = $books[$i]->getImg();
        }

        return $data;
    }

    /**
     * readOneBook.
     *
     * @return array
     *               An associative array with key 'book' containing the details of a single book:
     *               - book: array{
     *               id: int,
     *               title: string|null,
     *               isbn: string|null,
     *               author: string|null,
     *               img: string|null
     *               }
     *
     * @phpstan-return array{
     *   book: array{
     *     id: int,
     *     title: string|null,
     *     isbn: string|null,
     *     author: string|null,
     *     img: string|null
     *   }
     * }
     */
    public function readOneBook(int $id): array
    {
        $data = [
            'book' => [
                'id' => $id,
                'title' => '',
                'isbn' => null,
                'author' => null,
                'img' => null,
            ],
        ];

        // If id is less then 0
        if ($id < 0) {
            return $data;
        }

        $book = $this->findOneBy(['id' => $id]);

        // If no book is found
        if (null === $book) {
            return $data;
        }

        $data['book']['title'] = $book->getTitle();
        $data['book']['isbn'] = $book->getISBN();
        $data['book']['author'] = $book->getAuthor();
        $data['book']['img'] = $book->getImg();

        return $data;
    }

    /**
     * readOneBookISBN.
     *
     * @return array
     *               An associative array with key 'book' containing the details of a single book:
     *               - book: array{
     *               id: int|null,
     *               title: string|null,
     *               isbn: string,
     *               author: string|null,
     *               img: string|null
     *               }
     *
     * @phpstan-return array{
     *   book: array{
     *     id: int|null,
     *     title: string|null,
     *     isbn: string,
     *     author: string|null,
     *     img: string|null
     *   }
     * }
     */
    public function readOneBookISBN(string $isbn): array
    {
        $data = [
            'book' => [
                'id' => -1,
                'title' => '',
                'isbn' => $isbn,
                'author' => null,
                'img' => null,
            ],
        ];

        // isbn is not 13 digits return the empty data
        if (13 != strlen($isbn)) {
            return $data;
        }

        $book = $this->findOneBy(['isbn' => $isbn]);

        // If no book is found
        if (null === $book) {
            return $data;
        }

        $data['book']['id'] = $book->getId();
        $data['book']['title'] = $book->getTitle();
        $data['book']['author'] = $book->getAuthor();
        $data['book']['img'] = $book->getImg();

        return $data;
    }
}
