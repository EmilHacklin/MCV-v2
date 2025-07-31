<?php

namespace App\Tests\Controller;

use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

class BookControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RouterInterface $router;
    private ManagerRegistry $doctrine;
    private ?int $createdBookId = null; // Store created book ID

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Retrieve the client
        $this->client = static::createClient();

        // Retrieve the container
        $container = $this->client->getContainer();

        // Retrieve router service
        // @phpstan-ignore-next-line
        $this->router = $container->get('router');

        // Get the ManagerRegistry service from container
        // @phpstan-ignore-next-line
        $this->doctrine = $container->get('doctrine');
    }

    /**
     * tearDown
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->createdBookId !== null) {
            // Generate delete URL
            $deleteUrl = $this->router->generate('book_delete_post', ['id' => $this->createdBookId]);

            // Send delete request
            $this->client->request('POST', $deleteUrl, ['id' => $this->createdBookId]);

            // Reset the ID to prevent repeated deletions
            $this->createdBookId = null;
        }
    }

    /**
     * testBookCreatePOST
     *
     * Test book_create_post route
     *
     * @return void
     */
    private function testBookCreatePOST(): void
    {
        $bookRepository = new BookRepository($this->doctrine);

        // book_create_post

        // Generate URL from route name
        $url = $this->router->generate('book_create_post');

        // Create a mock UploadedFile
        $file = $this->createMock(UploadedFile::class);
        $file->method('isValid')->willReturn(true);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientOriginalName')->willReturn('test_image.jpg');
        $file->method('guessExtension')->willReturn('jpg');

        $formData = [
            'id' => 0,
            'title' => 'test book',
            'isbn' => '0000000000000',
            'author' => 'test author',
            'img' => $file,
        ];

        // Send POST request to the route
        $this->client->request('POST', $url, $formData);

        // Assert that response is a redirect (302 status)
        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect());

        $data = $bookRepository->readOneBookISBN('0000000000000');

        $this->assertEquals('test book', $data['book']['title']);
        $this->assertEquals('0000000000000', $data['book']['isbn']);
        $this->assertEquals('test author', $data['book']['author']);

        // After creating the book, store its ID
        $this->createdBookId = $data['book']['id'];

        // Test if title is null

        $formData = [
            'id' => 0,
            'title' => '',
            'isbn' => '000000000001',
            'author' => 'test author',
            'img' => $file,
        ];

        // Send POST request to the route
        $this->client->request('POST', $url, $formData);

        $data = $bookRepository->readOneBookISBN('0000000000001');

        $this->assertNull($data['book']['author']);
    }

    /**
     * testBookUpdatePOST
     *
     * Test book_update_post route
     *
     * @return void
     */
    private function testBookUpdatePOST(): void
    {
        $bookRepository = new BookRepository($this->doctrine);

        // Create a mock UploadedFile
        $file = $this->createMock(UploadedFile::class);
        $file->method('isValid')->willReturn(true);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientOriginalName')->willReturn('test_image1.jpg');
        $file->method('guessExtension')->willReturn('jpg');

        $formData = [
            'id' => $this->createdBookId,
            'title' => 'test book 2',
            'isbn' => '0000000000001',
            'author' => 'test author 2',
            'img' => $file,
        ];

        // Generate URL from route name
        $url = $this->router->generate('book_update_post', ['id' => (int) $this->createdBookId]);

        // Send POST request to the route
        $this->client->request('POST', $url, $formData);

        // Assert that response is a redirect (302 status)
        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect());

        $data = $bookRepository->readOneBook((int) $this->createdBookId);

        $this->assertEquals('test book 2', $data['book']['title']);
        $this->assertEquals('0000000000001', $data['book']['isbn']);
        $this->assertEquals('test author 2', $data['book']['author']);

        //Test if book don't exist
        $data = $bookRepository->readAllBooks();

        // Extract all existing IDs
        $existingIds = array_map(function ($book) {
            return $book['id'];
        }, $data['books']);

        // Find the smallest missing integer starting from 1
        $missingId = null;
        for ($i = 1; ; $i++) {
            if (!in_array($i, $existingIds, true)) {
                $missingId = $i;
                break;
            }
        }

        $formData = [
            'id' => (int) $missingId,
            'title' => 'test book 1',
            'isbn' => '0000000000000',
            'author' => 'test author 1',
            'img' => $file,
        ];

        // Generate URL from route name
        $url = $this->router->generate('book_update_post', ['id' => (int) $missingId]);

        // Send POST request to the route
        $this->client->request('POST', $url, $formData);

        // Assert that response is a redirect (302 status)
        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect());

        $data = $bookRepository->readOneBook((int) $missingId);

        $this->assertNull($data['book']['author']);

        // Test if title is null
        $formData = [
            'id' => $this->createdBookId,
            'title' => '',
            'isbn' => '0000000000000',
            'author' => 'test author 1',
            'img' => $file,
        ];

        // Generate URL from route name
        $url = $this->router->generate('book_update_post', ['id' => (int) $this->createdBookId]);

        // Send POST request to the route
        $this->client->request('POST', $url, $formData);

        // Assert that response is a redirect (302 status)
        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect());

        $data = $bookRepository->readOneBook((int) $this->createdBookId);

        $this->assertEquals('test book 2', $data['book']['title']);
        $this->assertEquals('0000000000001', $data['book']['isbn']);
        $this->assertEquals('test author 2', $data['book']['author']);
    }

    /**
     * testBookDeletePOST
     *
     * Test book_delete_post route
     *
     * @return void
     */
    private function testBookDeletePOST(): void
    {
        $formData = [
            'id' => (int) $this->createdBookId,
        ];

        //book_delete_post

        // Generate URL from route name
        $url = $this->router->generate('book_delete_post', ['id' => (int)$this->createdBookId]);

        // Send POST request to the route
        $this->client->request('POST', $url, $formData);

        // Assert that response is a redirect (302 status)
        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect());

        // Reset the ID to prevent repeated deletions
        $this->createdBookId = null;

        //Test to delete book that don't exists

        // Send POST request to the route
        $this->client->request('POST', $url, $formData);

        // Assert that response is a redirect (302 status)
        $response = $this->client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testCRUD
     *
     * @return void
     */
    public function testCRUD(): void
    {
        // Test Create Book
        $this->testBookCreatePOST();

        // Test Update Book
        $this->testBookUpdatePOST();

        // Test Delete Book
        $this->testBookDeletePOST();
    }

    /**
     * testLibrary
     *
     * Test library route
     *
     * @return void
     */
    public function testLibrary(): void
    {
        //library

        // Generate URL from route name
        $url = $this->router->generate('library');

        // Send a GET request to the route
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Book: Library', $crawler->filter('title')->text());
    }

    /**
     * testBookCreateGET
     *
     * Test book_create_get route
     *
     * @return void
     */
    public function testBookCreateGET(): void
    {
        //book_create_get

        // Generate URL from route name
        $url = $this->router->generate('book_create_get');

        // Send a GET request to the route
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Book: Create', $crawler->filter('title')->text());
    }

    /**
     * testShowAll
     *
     * Test book_show_all route
     *
     * @return void
     */
    public function testShowAll(): void
    {
        //book_show_all

        // Generate URL from route name
        $url = $this->router->generate('book_show_all');

        // Send a GET request to the route
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Book: Show Library', $crawler->filter('title')->text());
    }

    /**
     * testShowOne
     *
     * Test book_show_one route
     *
     * @return void
     */
    public function testShowOne(): void
    {
        // Retrieve the container
        $container = $this->client->getContainer();

        // Get the ManagerRegistry service from container
        /** @var ManagerRegistry $doctrine */
        $doctrine = $container->get('doctrine');

        $bookRepository = new BookRepository($doctrine);

        $data = $bookRepository->readAllBooks();

        //If there is books in the library
        $id = null;

        if (count($data['books']) > 0) {
            $id = $data['books'][0]['id'];
        }

        if (null === $id) {
            $id = '0';
        }

        //book_show_one

        // Generate URL from route name
        $url = $this->router->generate('book_show_one', ['id' => $id]);

        // Send a GET request to the route
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Book: Show Book', $crawler->filter('title')->text());
    }

    /**
     * testShowAllUpdate
     *
     * Test book_show_all_update route
     *
     * @return void
     */
    public function testShowAllUpdate(): void
    {
        //book_show_all_update

        // Generate URL from route name
        $url = $this->router->generate('book_show_all_update');

        // Send a GET request to the route
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Book: Show Library Update', $crawler->filter('title')->text());
    }

    /**
     * testBookUpdateGET
     *
     * Test book_update_get route
     *
     * @return void
     */
    public function testBookUpdateGET(): void
    {
        $bookRepository = new BookRepository($this->doctrine);

        $data = $bookRepository->readAllBooks();

        //If there is books in the library
        $id = null;

        if (count($data['books']) > 0) {
            $id = $data['books'][0]['id'];
        }

        if (null === $id) {
            $id = '0';
        }

        //book_update_get

        // Generate URL from route name
        $url = $this->router->generate('book_update_get', ['id' => $id]);

        // Send a GET request to the route
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Book: Update book', $crawler->filter('title')->text());
    }

    /**
     * testBookShowAllDeleteGET
     *
     * Test book_show_all_delete_get route
     *
     * @return void
     */
    public function testBookShowAllDeleteGET(): void
    {
        //book_show_all_delete_get

        // Generate URL from route name
        $url = $this->router->generate('book_show_all_delete_get');

        // Send a GET request to the route
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Book: Show Library Delete', $crawler->filter('title')->text());
    }
}
