<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class BlackJackControllerTest extends WebTestCase
{
    /**
     * testGameStart
     *
     * Test game_start route
     *
     * @return void
     */
    public function testGameStart(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('game_start');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Black Jack:', $crawler->filter('title')->text());
    }

    /**
     * testBlackJackDoc
     *
     * Test game_doc route
     *
     * @return void
     */
    public function testBlackJackDoc(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('game_doc');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Black Jack: Doc', $crawler->filter('title')->text());
    }

    /**
     * testBlackJackStart
     *
     * Test game_black_jack route
     *
     * @return void
     */
    public function testBlackJackStart(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('game_black_jack');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Black Jack: Game', $crawler->filter('title')->text());
    }

    /**
     * testBlackJackHit
     *
     * Test black_jack_hit route
     *
     * @return void
     */
    public function testBlackJackHit(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('black_jack_hit');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());

        for ($i = 0; $i < 5; $i++) {
            // Send POST request to the route
            $client->request('POST', $url);

            // Assert that response is a redirect (302 status)
            $response = $client->getResponse();
            $this->assertTrue($response->isRedirect());
        }
    }

    /**
     * testBlackJackStay
     *
     * Test black_jack_stay route
     *
     * @return void
     */
    public function testBlackJackStay(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('black_jack_stay');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testBlackJackReset
     *
     * Test black_jack_reset route
     *
     * @return void
     */
    public function testBlackJackReset(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('black_jack_reset');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }
}
