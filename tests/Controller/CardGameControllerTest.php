<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class CardGameControllerTest extends WebTestCase
{
    /**
     * testCardStart
     *
     * Test card_start route
     *
     * @return void
     */
    public function testCardStart(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //card_start

        // Generate URL from route name
        $url = $router->generate('card_start');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Card Game:', $crawler->filter('title')->text());
    }

    /**
     * testCardSession
     *
     * Test card_session route
     *
     * @return void
     */
    public function testCardSession(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //card_start

        // Generate URL from route name
        $url = $router->generate('card_session');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Card Game: Session info', $crawler->filter('title')->text());
    }

    /**
     * testCardSessionDelete
     *
     * Test card_session_delete route
     *
     * @return void
     */
    public function testCardSessionDelete(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //card_start

        // Generate URL from route name
        $url = $router->generate('card_session_delete');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Card Game: Session info', $crawler->filter('title')->text());
    }

    /**
     * testCardDeck
     *
     * Test card_deck route
     *
     * @return void
     */
    public function testCardDeck(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //card_start

        // Generate URL from route name
        $url = $router->generate('card_deck');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Card Game: Deck', $crawler->filter('title')->text());
    }

    /**
     * testCardDeckShuffle
     *
     * Test card_deck_shuffle route
     *
     * @return void
     */
    public function testCardDeckShuffle(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //card_start

        // Generate URL from route name
        $url = $router->generate('card_deck_shuffle');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Card Game: Deck', $crawler->filter('title')->text());
    }

    /**
     * testCardDeckDraw
     *
     * Test card_deck_draw route
     *
     * @return void
     */
    public function testCardDeckDraw(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //card_start

        // Generate URL from route name
        $url = $router->generate('card_deck_draw');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Card Game: Draw', $crawler->filter('title')->text());
    }

    /**
     * testCardDeckDrawMany
     *
     * Test card_deck_draw_many route
     *
     * @return void
     */
    public function testCardDeckDrawMany(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //card_start

        // Generate URL from route name
        $url = $router->generate('card_deck_draw_many', ['num' => 5]);

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Card Game: Draw', $crawler->filter('title')->text());
    }
}
