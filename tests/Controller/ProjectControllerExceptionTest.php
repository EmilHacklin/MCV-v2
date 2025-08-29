<?php

namespace App\Tests\Controller;

use App\Game\BlackJack;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class ProjectControllerExceptionTest extends WebTestCase
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
     * testProjectPlayerInitHighException
     *
     * Test the exception handling for higher bound
     *
     * @return void
     */
    public function testProjectPlayerInitHighException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('proj_player_init', ['num' => 100]);

        // Error message to look for
        $errorMessage = "Can't have more then ".BlackJack::MAX_PLAYERS.'players!';

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testProjectPlayerInitLowException
     *
     * Test the exception handling for lower bound
     *
     * @return void
     */
    public function testProjectPlayerInitLowException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('proj_player_init', ['num' => 0]);

        // Error message to look for
        $errorMessage = "Can't have less than 1 player!";

        // Disable exception catching
        $client->catchExceptions(false);

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testProjBlackJackGameNoSession
     *
     * Test proj_BlackJack_game route with no session
     *
     * @return void
     */
    public function testProjBlackJackGameNoSession(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL
        $url = $router->generate('proj_BlackJack_game');

        // Make the request
        $client->request('GET', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackBetNoSession
     *
     * Test proj_BlackJack_Bet route with no session
     *
     * @return void
     */
    public function testProjBlackJackBetNoSession(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_Bet');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackDoubleDownNoSession
     *
     * Test proj_BlackJack_DoubleDown route with no session
     *
     * @return void
     */
    public function testProjBlackJackDoubleDownNoSession(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_DoubleDown');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackHitNoSession
     *
     * Test proj_BlackJack_Hit route with no session
     *
     * @return void
     */
    public function testProjBlackJackHitNoSession(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_Hit');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackStayNoSession
     *
     * Test proj_BlackJack_Stay route with no session
     *
     * @return void
     */
    public function testProjBlackJackStayNoSession(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_Stay');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }
}
