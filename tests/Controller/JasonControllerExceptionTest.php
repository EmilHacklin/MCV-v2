<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class JasonControllerExceptionTest extends WebTestCase
{
    /**
     * testException
     *
     * Test the exception handling
     *
     * @param KernelBrowser $client
     * @param  string $url
     * @param  string $errorMessage
     * @return void
     */
    private function testPOSTException(KernelBrowser $client, string $url, string $errorMessage = ""): void
    {
        // Disable exception catching
        $client->catchExceptions(false);

        //Test the exception handling
        $this->expectException(\Exception::class);
        if ($errorMessage != "") {
            $this->expectExceptionMessage($errorMessage);
        }

        // Send a GET request to the route
        $client->request('POST', $url);

        // Re-enable exception catching
        $client->catchExceptions(true);
    }

    /**
     * resetDeck
     *
     * Reset the deck for further testing
     *
     * @return void
     */
    private function resetDeck(KernelBrowser $client, RouterInterface $router): void
    {
        // Generate URL from route name
        $url = $router->generate('card_session_delete');

        // Send a GET request to the route
        $client->request('GET', $url);
    }

    /**
     * testApiDeckDrawEmptyException
     *
     * Test the exception handling for drawing when the deck is empty
     *
     * @return void
     */
    public function testApiDeckDrawEmptyException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //Reset deck
        $this->resetDeck($client, $router);

        // Generate URL from route name
        $url = $router->generate('api/deck/draw/:number', ['num' => 52]);

        // Send a GET request to the route
        $client->request('POST', $url);

        // Generate URL from route name
        $url = $router->generate('api/deck/draw');

        // Error message to look for
        $errorMessage = "Can't draw more cards as the deck is empty!";

        $this->testPOSTException($client, $url, $errorMessage);
    }

    /**
     * testApiDeckDrawManyHighException
     *
     * Test the exception handling for higher bound
     *
     * @return void
     */
    public function testApiDeckDrawManyHighException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/deck/draw/:number', ['num' => 53]);

        // Error message to look for
        $errorMessage = "Can't draw more than cards in deck!";

        // Disable exception catching
        $client->catchExceptions(false);

        $this->testPOSTException($client, $url, $errorMessage);
    }

    /**
     * testApiDeckDrawManyLowException
     *
     * Test the exception handling for lower bound
     *
     * @return void
     */
    public function testApiDeckDrawManyLowException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('api/deck/draw/:number', ['num' => 0]);

        // Error message to look for
        $errorMessage = "Can't draw less than 1 card!";

        $this->testPOSTException($client, $url, $errorMessage);
    }

    /**
     * testApiDeckDrawManyToManyException
     *
     * Test the exception handling for drawing more cards then the deck have
     *
     * @return void
     */
    public function testApiDeckDrawManyToManyException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //Reset deck
        $this->resetDeck($client, $router);

        // Generate URL from route name
        $url = $router->generate('api/deck/draw/:number', ['num' => 51]);

        // Send a GET request to the route
        $client->request('POST', $url);

        //Test the exception handling for drawing more cards then the deck have

        // Generate URL from route name
        $url = $router->generate('api/deck/draw/:number', ['num' => 2]);

        $this->testPOSTException($client, $url);
    }

    /**
     * testApiDeckDrawManyEmptyException
     *
     * Test the exception handling for drawing when the deck is empty
     *
     * @return void
     */
    public function testApiDeckDrawManyEmptyException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //Reset deck
        $this->resetDeck($client, $router);

        // Generate URL from route name
        $url = $router->generate('api/deck/draw/:number', ['num' => 52]);

        // Send a GET request to the route
        $client->request('POST', $url);

        // Generate URL from route name
        $url = $router->generate('api/deck/draw/:number', ['num' => 1]);

        // Error message to look for
        $errorMessage = "Can't draw more cards as the deck is empty!";

        $this->testPOSTException($client, $url, $errorMessage);
    }
}
