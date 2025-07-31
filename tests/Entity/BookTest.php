<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Book;

/**
 * Test cases for class Book.
 */
class BookTest extends TestCase
{
    /**
     * testCreateObject
     *
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     *
     * @return void
     */
    public function testCreateObject(): void
    {
        $book = new Book();
        $this->assertInstanceOf(Book::class, $book);
    }

    /**
     * testSetAndGetMethods
     *
     * Test the set and get methods
     *
     * @return void
     */
    public function testSetAndGetMethods(): void
    {
        $book = new Book();
        $book->setTitle("Test");
        $book->setISBN("1234567891234");
        $book->setAuthor("Test");
        $book->setImg("Test");

        $this->assertNull($book->getId());
        $this->assertEquals("Test", $book->getTitle());
        $this->assertEquals("1234567891234", $book->getISBN());
        $this->assertEquals("Test", $book->getAuthor());
        $this->assertEquals("Test", $book->getImg());

        //Test the setters is null
        $book = new Book();
        $book->setISBN(null);
        $book->setAuthor(null);
        $book->setImg(null);

        $this->assertNull($book->getISBN());
        $this->assertNull($book->getAuthor());
        $this->assertNull($book->getImg());
    }
}
