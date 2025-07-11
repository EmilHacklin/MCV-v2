<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class CardGameControllerExceptionTest extends WebTestCase
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
    private function testException(KernelBrowser $client, string $url, string $errorMessage = ""): void
    {
        // Disable exception catching
        $client->catchExceptions(false);

        //Test the exception handling
        $this->expectException(\Exception::class);
        if ($errorMessage != "") {
            $this->expectExceptionMessage($errorMessage);
        }

        // Send a GET request to the route
        $client->request('GET', $url);

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
     * testCardDeckDrawEmptyException
     *
     * Test the exception handling for drawing when the deck is empty
     *
     * @return void
     */
    public function testCardDeckDrawEmptyException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //Reset deck
        $this->resetDeck($client, $router);

        // Generate URL from route name
        $url = $router->generate('card_deck_draw_many', ['num' => 52]);

        // Send a GET request to the route
        $client->request('GET', $url);

        // Generate URL from route name
        $url = $router->generate('card_deck_draw');

        // Error message to look for
        $errorMessage = "Can't draw more cards as the deck is empty!";

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testCardDeckDrawManyHighException
     *
     * Test the exception handling for higher bound
     *
     * @return void
     */
    public function testCardDeckDrawManyHighException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('card_deck_draw_many', ['num' => 53]);

        // Error message to look for
        $errorMessage = "Can't draw more than cards in deck!";

        // Disable exception catching
        $client->catchExceptions(false);

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testCardDeckDrawManyLowException
     *
     * Test the exception handling for lower bound
     *
     * @return void
     */
    public function testCardDeckDrawManyLowException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('card_deck_draw_many', ['num' => 0]);

        // Error message to look for
        $errorMessage = "Can't draw less than 1 card!";

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testCardDeckDrawManyToManyException
     *
     * Test the exception handling for drawing more cards then the deck have
     *
     * @return void
     */
    public function testCardDeckDrawManyToManyException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //Reset deck
        $this->resetDeck($client, $router);

        // Generate URL from route name
        $url = $router->generate('card_deck_draw_many', ['num' => 51]);

        // Send a GET request to the route
        $client->request('GET', $url);

        //Test the exception handling for drawing more cards then the deck have

        // Generate URL from route name
        $url = $router->generate('card_deck_draw_many', ['num' => 2]);

        $this->testException($client, $url);
    }

    /**
     * testCardDeckDrawManyEmptyException
     *
     * Test the exception handling for drawing when the deck is empty
     *
     * @return void
     */
    public function testCardDeckDrawManyEmptyException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //Reset deck
        $this->resetDeck($client, $router);

        // Generate URL from route name
        $url = $router->generate('card_deck_draw_many', ['num' => 52]);

        // Send a GET request to the route
        $client->request('GET', $url);

        // Generate URL from route name
        $url = $router->generate('card_deck_draw_many', ['num' => 1]);

        // Error message to look for
        $errorMessage = "Can't draw more cards as the deck is empty!";

        $this->testException($client, $url, $errorMessage);
    }
}
