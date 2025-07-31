<?php

namespace App\Tests\Controller;

use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class JasonControllerTest extends WebTestCase
{
    /**
     * testApi
     *
     * Test api route
     *
     * @return void
     */
    public function testApi(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // $response = $client->getResponse();
        // echo $response->getContent();

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('MVC: Api', $crawler->filter('title')->text());
    }

    /**
     * testApiQuote
     *
     * Test api/quote route
     *
     * @return void
     */
    public function testApiQuote(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/quote');

        // Send a GET request to the route
        $client->request('GET', $url);

        // Assert response status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Get the response content
        $content = $client->getResponse()->getContent();

        $data = null;

        // Decode JSON
        if (is_string($content)) {
            $data = json_decode($content, true);
        }

        // Assert that JSON decoding was successful
        $this->assertNotNull($data, 'Failed to decode JSON.');
        $this->assertIsArray($data, 'Decoded JSON data is not an array.');

        // Verify that required keys exist
        $this->assertArrayHasKey('quote', $data);
        $this->assertArrayHasKey('author', $data);
        $this->assertArrayHasKey('timestamp', $data);

        // Assert that 'quote' and 'author' are not empty
        $this->assertNotEmpty($data['quote']);
        $this->assertNotEmpty($data['author']);
    }

    /**
     * testApiDeck
     *
     * Test api/deck route
     *
     * @return void
     */
    public function testApiDeck(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/deck');

        // Send a GET request to the route
        $client->request('GET', $url);

        // Assert response status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Get the response content
        $content = $client->getResponse()->getContent();

        $data = null;

        // Decode JSON
        if (is_string($content)) {
            $data = json_decode($content, true);
        }

        // Assert that JSON decoding was successful
        $this->assertNotNull($data, 'Failed to decode JSON.');
        $this->assertIsArray($data, 'Decoded JSON data is not an array.');

        // Verify that required keys exist
        $this->assertArrayHasKey('deck', $data);

        // Assert the deck is an array
        $this->assertIsArray($data['deck']);
    }

    /**
     * testApiDeckShuffle
     *
     * Test api/deck/shuffle route
     *
     * @return void
     */
    public function testApiDeckShuffle(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/deck/shuffle');

        // Send a POST request to the route
        $client->request('POST', $url);

        // Get the response content
        $content = $client->getResponse()->getContent();

        $data = null;

        // Decode JSON
        if (is_string($content)) {
            $data = json_decode($content, true);
        }

        // Assert that JSON decoding was successful
        $this->assertNotNull($data, 'Failed to decode JSON.');
        $this->assertIsArray($data, 'Decoded JSON data is not an array.');

        // Verify that required keys exist
        $this->assertArrayHasKey('deck', $data);

        // Assert the deck is an array
        $this->assertIsArray($data['deck']);
    }

    /**
     * testApiDeckDraw
     *
     * Test api/deck/draw route
     *
     * @return void
     */
    public function testApiDeckDraw(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/deck/draw');

        // Send a POST request to the route
        $client->request('POST', $url);

        // Get the response content
        $content = $client->getResponse()->getContent();

        $data = null;

        // Decode JSON
        if (is_string($content)) {
            $data = json_decode($content, true);
        }

        // Assert that JSON decoding was successful
        $this->assertNotNull($data, 'Failed to decode JSON.');
        $this->assertIsArray($data, 'Decoded JSON data is not an array.');

        // Verify that required keys exist
        $this->assertArrayHasKey('hand', $data);
        $this->assertArrayHasKey('deckCount', $data);

        // Assert the hand is an array
        $this->assertIsArray($data['hand']);

        // Assert the deckCount is not empty
        $this->assertNotEmpty($data['deckCount']);
    }

    /**
     * testApiDeckDrawMany
     *
     * Test api/deck/draw/:number route
     *
     * @return void
     */
    public function testApiDeckDrawMany(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/deck/draw/:number', ['num' => 5]);

        // Send a POST request to the route
        $client->request('POST', $url);

        // Get the response content
        $content = $client->getResponse()->getContent();

        $data = null;

        // Decode JSON
        if (is_string($content)) {
            $data = json_decode($content, true);
        }

        // Assert that JSON decoding was successful
        $this->assertNotNull($data, 'Failed to decode JSON.');
        $this->assertIsArray($data, 'Decoded JSON data is not an array.');

        // Verify that required keys exist
        $this->assertArrayHasKey('hand', $data);
        $this->assertArrayHasKey('deckCount', $data);

        // Assert the hand is an array
        $this->assertIsArray($data['hand']);

        // Assert the deckCount is not empty
        $this->assertNotEmpty($data['deckCount']);
    }

    /**
     * testApiBlackJack
     *
     * Test api/game route
     *
     * @return void
     */
    public function testApiBlackJack(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/game');

        // Send a GET request to the route
        $client->request('GET', $url);

        // Get the response content
        $content = $client->getResponse()->getContent();

        $data = null;

        // Decode JSON
        if (is_string($content)) {
            $data = json_decode($content, true);
        }

        // Assert that JSON decoding was successful
        $this->assertNotNull($data, 'Failed to decode JSON.');
        $this->assertIsArray($data, 'Decoded JSON data is not an array.');

        // Verify that required keys exist
        $this->assertArrayHasKey('numOfPlayers', $data);
        $this->assertArrayHasKey('playersCards', $data);
        $this->assertArrayHasKey('playersHandValue', $data);
        $this->assertArrayHasKey('dealerCards', $data);
        $this->assertArrayHasKey('dealerHandValue', $data);
        $this->assertArrayHasKey('gameStates', $data);

        // Assert is an array
        $this->assertIsArray($data['playersCards']);
        $this->assertIsArray($data['playersCards'][0]);
        $this->assertIsArray($data['playersHandValue']);
        $this->assertIsArray($data['dealerCards']);
        $this->assertIsArray($data['gameStates']);

        // Assert is not empty
        $this->assertNotNull($data['numOfPlayers']);
        $this->assertNotEmpty($data['playersCards'][0]);
        $this->assertNotNull($data['playersHandValue'][0]);
        $this->assertNotEmpty($data['dealerCards']);
        $this->assertNotNull($data['dealerHandValue']);
        $this->assertNotEmpty($data['gameStates']);
        $this->assertNotNull($data['gameStates'][0]);
    }

    /**
     * testApiLibraryBooks
     *
     * Test api/library/books route
     *
     * @return void
     */
    public function testApiLibraryBooks(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/library/books');

        // Send a GET request to the route
        $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Get the response content
        $content = $client->getResponse()->getContent();

        $data = null;

        // Decode JSON
        if (is_string($content)) {
            $data = json_decode($content, true);
        }

        // Assert that JSON decoding was successful
        $this->assertNotNull($data, 'Failed to decode JSON.');
        $this->assertIsArray($data, 'Decoded JSON data is not an array.');

        // Verify that required keys exist
        $this->assertArrayHasKey('books', $data);
    }

    /**
     * testApiLibraryBookISBN
     *
     * Test api/library/book/isbn route
     *
     * @return void
     */
    public function testApiLibraryBookISBN(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Boot the kernel
        $kernel = self::bootKernel();

        // Get the ManagerRegistry service from container
        /** @var ManagerRegistry $doctrine */
        $doctrine = $kernel->getContainer()->get('doctrine');
        $bookRepository = new BookRepository($doctrine);

        $data = $bookRepository->readAllBooks();

        //If there is books in the library
        $isbn = null;

        if (count($data['books']) > 0) {
            $isbn = $data['books'][0]['isbn'];
        }

        if (null === $isbn) {
            $isbn = '0';
        }

        // Generate URL from route name
        $url = $router->generate('api/library/book/isbn', ['isbn' => $isbn]);

        // Send a GET request to the route
        $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Get the response content
        $content = $client->getResponse()->getContent();

        $data = null;

        // Decode JSON
        if (is_string($content)) {
            $data = json_decode($content, true);
        }

        // Assert that JSON decoding was successful
        $this->assertNotNull($data, 'Failed to decode JSON.');
        $this->assertIsArray($data, 'Decoded JSON data is not an array.');

        // Verify that required keys exist
        $this->assertArrayHasKey('book', $data);
        $this->assertArrayHasKey('id', $data['book']);
        $this->assertArrayHasKey('title', $data['book']);
        $this->assertArrayHasKey('isbn', $data['book']);
        $this->assertArrayHasKey('author', $data['book']);
        $this->assertArrayHasKey('img', $data['book']);
    }
}
