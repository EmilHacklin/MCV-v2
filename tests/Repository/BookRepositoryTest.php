<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookRepository;
use App\Entity\Book;

/**
 * Test cases for class BookRepository.
 */
class BookRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private BookRepository $repository;
    private ?int $createdBookId = null; // Store created book ID

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        self::bootKernel();

        $kernel = self::$kernel;

        // Check if kernel is not null
        if ($kernel === null) {
            throw new \RuntimeException('Kernel is not initialized.');
        }

        // Access the container
        $container = $kernel->getContainer();

        // Get the Doctrine registry service
        $doctrine = $container->get('doctrine');

        // Get the EntityManager from the container
        // @phpstan-ignore-next-line
        $this->entityManager = $doctrine->getManager();

        // Initialize the repository
        // @phpstan-ignore-next-line
        $this->repository = $this->entityManager->getRepository(Book::class);
    }

    /**
     * getNonExistantId
     *
     * @return int
     */
    private function getNonExistantId(): int
    {
        // Read all the books
        $data = $this->repository->readAllBooks();

        // Extract all existing IDs
        $existingIds = array_map(function ($book) {
            return $book['id'];
        }, $data['books']);

        // Find the smallest missing integer starting from 1
        $missingId = null;
        for ($i = 1; true; $i++) {
            if (!in_array($i, $existingIds, true)) {
                $missingId = $i;
                break;
            }
        }

        return (int) $missingId;
    }

    /**
     * testSaveBook
     *
     * @return void
     */
    private function testSaveBook(): void
    {
        // save a book object
        $this->repository->saveBook('Title 1', '1000000000000', 'Author 1', 'Image 1');

        // Read the book
        $data = $this->repository->readOneBookISBN('1000000000000');

        $this->assertNotNull($data['book']['id']);
        $this->assertEquals("Title 1", $data['book']['title']);
        $this->assertEquals("1000000000000", $data['book']['isbn']);
        $this->assertEquals("Author 1", $data['book']['author']);
        $this->assertEquals("Image 1", $data['book']['img']);

        // After creating the book, store its ID
        $this->createdBookId = $data['book']['id'];

        // Test title is null
        $this->repository->saveBook('', '1000000000001', 'Author 2', 'Image 2');

        // Read the book
        $data = $this->repository->readOneBookISBN('1000000000001');

        $this->assertEquals(-1, $data['book']['id']);
        $this->assertEquals('', $data['book']['title']);
        $this->assertEquals("1000000000001", $data['book']['isbn']);
        $this->assertNull($data['book']['author']);
        $this->assertNull($data['book']['img']);
    }

    /**
     * testReadAllBooks
     *
     * @return void
     */
    private function testReadAllBooks(): void
    {
        // Read all the books
        $data = $this->repository->readAllBooks();

        $this->assertGreaterThan(0, count($data['books']));
    }

    /**
     * testReadOneBookISBN
     *
     * @return void
     */
    private function testReadOneBookISBN(): void
    {
        // Read the book
        $data = $this->repository->readOneBookISBN('1000000000000');

        $this->assertNotNull($data['book']['id']);
        $this->assertEquals("Title 1", $data['book']['title']);
        $this->assertEquals("1000000000000", $data['book']['isbn']);
        $this->assertEquals("Author 1", $data['book']['author']);
        $this->assertEquals("Image 1", $data['book']['img']);

        // Test wrong ISBN digit length

        // Read the book
        $data = $this->repository->readOneBookISBN('0');

        $this->assertEquals(-1, $data['book']['id']);
        $this->assertEquals('', $data['book']['title']);
        $this->assertEquals("0", $data['book']['isbn']);
        $this->assertNull($data['book']['author']);
        $this->assertNull($data['book']['img']);

        // Test Book not found

        // Read the book
        $data = $this->repository->readOneBookISBN('1000000000001');

        $this->assertEquals(-1, $data['book']['id']);
        $this->assertEquals('', $data['book']['title']);
        $this->assertEquals("1000000000001", $data['book']['isbn']);
        $this->assertNull($data['book']['author']);
        $this->assertNull($data['book']['img']);
    }

    /**
     * testReadOneBook
     *
     * @return void
     */
    private function testReadOneBook(): void
    {
        // Read one book based on id
        $data = $this->repository->readOneBook((int) $this->createdBookId);

        $this->assertEquals((int) $this->createdBookId, $data['book']['id']);
        $this->assertEquals("Title 1", $data['book']['title']);
        $this->assertEquals("1000000000000", $data['book']['isbn']);
        $this->assertEquals("Author 1", $data['book']['author']);
        $this->assertEquals("Image 1", $data['book']['img']);

        // Test if id is negative

        // Read one book based on id
        $data = $this->repository->readOneBook(-1);

        $this->assertEquals(-1, $data['book']['id']);
        $this->assertEquals('', $data['book']['title']);
        $this->assertNull($data['book']['isbn']);
        $this->assertNull($data['book']['author']);
        $this->assertNull($data['book']['img']);

        // Test if Book don't exists

        // Get id of Book that don't exist
        $missingId = $this->getNonExistantId();

        // Read one book based on id
        $data = $this->repository->readOneBook($missingId);

        $this->assertEquals($missingId, $data['book']['id']);
        $this->assertEquals('', $data['book']['title']);
        $this->assertNull($data['book']['isbn']);
        $this->assertNull($data['book']['author']);
        $this->assertNull($data['book']['img']);
    }

    /**
     * testReturnBook
     *
     * @return void
     */
    private function testReturnBook(): void
    {
        // Return a book object
        $book = $this->repository->returnBook('Title 1', '1000000000000', 'Author 1', 'Image 1');

        $this->assertNull($book->getId());
        $this->assertEquals("Title 1", $book->getTitle());
        $this->assertEquals("1000000000000", $book->getISBN());
        $this->assertEquals("Author 1", $book->getAuthor());
        $this->assertEquals("Image 1", $book->getImg());
    }

    /**
     * testUpdateBook
     *
     * @return void
     */
    private function testUpdateBook(): void
    {
        //Update book
        $this->repository->updateBook((int) $this->createdBookId, 'Title 2', '1000000000001', 'Author 2', 'Image 2');

        // Read one book based on id
        $data = $this->repository->readOneBook((int) $this->createdBookId);

        $this->assertEquals((int) $this->createdBookId, $data['book']['id']);
        $this->assertEquals("Title 2", $data['book']['title']);
        $this->assertEquals("1000000000001", $data['book']['isbn']);
        $this->assertEquals("Author 2", $data['book']['author']);
        $this->assertEquals("Image 2", $data['book']['img']);

        //Test to update book that don't exists

        // Get id of Book that don't exist
        $missingId = $this->getNonExistantId();

        // Read all the books
        $data = $this->repository->readAllBooks();

        $numBooks = count($data['books']);

        //Update book
        $this->repository->updateBook($missingId, 'Title 3', '1000000000002', 'Author 3', 'Image 3');

        // Read all the books
        $data = $this->repository->readAllBooks();

        // If update happen then the book would have been created
        $this->assertEquals(count($data['books']), $numBooks);

        // Test empty title

        //Update book
        $this->repository->updateBook((int) $this->createdBookId, '', '1000000000002', 'Author 3', 'Image 3');

        // Read one the books
        $data = $this->repository->readOneBook((int) $this->createdBookId);

        $this->assertEquals((int) $this->createdBookId, $data['book']['id']);
        $this->assertEquals("Title 2", $data['book']['title']);
        $this->assertEquals("1000000000001", $data['book']['isbn']);
        $this->assertEquals("Author 2", $data['book']['author']);
        $this->assertEquals("Image 2", $data['book']['img']);
    }

    /**
     * testDeleteBook
     *
     * @return void
     */
    private function testDeleteBook(): void
    {
        // Delete the book
        $this->repository->deleteBook((int)$this->createdBookId);

        // Read one the books
        $data = $this->repository->readOneBook((int) $this->createdBookId);

        $this->assertEquals((int) $this->createdBookId, $data['book']['id']);
        $this->assertEquals('', $data['book']['title']);
        $this->assertNull($data['book']['isbn']);
        $this->assertNull($data['book']['author']);
        $this->assertNull($data['book']['img']);

        // Test if book don't exists

        // Delete the book
        $this->repository->deleteBook((int) $this->createdBookId);

        // Reset the ID to prevent repeated deletions
        $this->createdBookId = null;
    }

    /**
     * tearDown
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if (null !== $this->createdBookId) {
            // Delete the book
            $this->repository->deleteBook($this->createdBookId);

            // Reset the ID to prevent repeated deletions
            $this->createdBookId = null;
        }
    }

    /**
     * testCRUD
     *
     * @return void
     */
    public function testCRUD(): void
    {
        // Test returning a Book Object
        $this->testReturnBook();

        // Test Create Book
        $this->testSaveBook();

        // Test Read Book
        $this->testReadAllBooks();
        $this->testReadOneBookISBN();
        $this->testReadOneBook();

        // Test Update Book
        $this->testUpdateBook();

        // Test Delete Book
        $this->testDeleteBook();
    }
}
