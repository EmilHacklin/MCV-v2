<?php

namespace App\Tests\Controller;

use App\Game\BlackJack;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class ProjectControllerTest extends WebTestCase
{
    /**
     * testProjBlackJackInit
     *
     * Test proj_BlackJack_init route
     *
     * @param  KernelBrowser $client
     * @param  RouterInterface $router
     * @return void
     */
    private function testProjBlackJackInit(KernelBrowser $client, RouterInterface $router): void
    {
        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_init');

        $postData = [
            'numOfPlayers' => 2,
            'Player 1' => 'Alice',
            'Player 2' => 'Bob',
            'Player 3' => 'Erik',
        ];

        // Send POST request to the route
        $client->request('POST', $url, $postData);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackGame
     *
     * Test proj_BlackJack_game route
     *
     * @param  KernelBrowser $client
     * @param  RouterInterface $router
     * @return void
     */
    private function testProjBlackJackGame(KernelBrowser $client, RouterInterface $router): void
    {
        // Generate URL
        $url = $router->generate('proj_BlackJack_game');

        // Make the request
        $crawler = $client->request('GET', $url);

        // Assert status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert content
        $this->assertStringContainsString('Project Black Jack: Game', $crawler->filter('title')->text());
    }

    /**
     * testProjBlackJackBet
     *
     * Test proj_BlackJack_Bet route
     *
     * @param  KernelBrowser $client
     * @param  RouterInterface $router
     * @return void
     */
    private function testProjBlackJackBet(KernelBrowser $client, RouterInterface $router): void
    {
        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_Bet');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackDoubleDown
     *
     * Test proj_BlackJack_DoubleDown route
     *
     * @param  KernelBrowser $client
     * @param  RouterInterface $router
     * @return void
     */
    private function testProjBlackJackDoubleDown(KernelBrowser $client, RouterInterface $router): void
    {
        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_DoubleDown');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackHit
     *
     * Test proj_BlackJack_Hit route
     *
     * @param  KernelBrowser $client
     * @param  RouterInterface $router
     * @return void
     */
    private function testProjBlackJackHit(KernelBrowser $client, RouterInterface $router): void
    {
        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_Hit');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackStay
     *
     * Test proj_BlackJack_Stay route
     *
     * @param  KernelBrowser $client
     * @param  RouterInterface $router
     * @return void
     */
    private function testProjBlackJackStay(KernelBrowser $client, RouterInterface $router): void
    {
        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_Stay');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjBlackJackNewGame
     *
     * Test proj_BlackJack_NewGame route
     *
     * @param  KernelBrowser $client
     * @param  RouterInterface $router
     * @return void
     */
    private function testProjBlackJackNewGame(KernelBrowser $client, RouterInterface $router): void
    {
        // Generate URL from route name
        $url = $router->generate('proj_BlackJack_NewGame');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testProjStart
     *
     * Test proj_start route
     *
     * @return void
     */
    public function testProjStart(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //proj_start

        // Generate URL from route name
        $url = $router->generate('proj_start');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Project Black Jack', $crawler->filter('title')->text());
    }

    /**
     * testProjDoc
     *
     * Test proj_doc route
     *
     * @return void
     */
    public function testProjDoc(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //proj_doc

        // Generate URL from route name
        $url = $router->generate('proj_doc');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Project Black Jack: Doc', $crawler->filter('title')->text());
    }

    /**
     * testProjPlayerInit
     *
     * Test proj_player_init route
     *
     * @return void
     */
    public function testProjPlayerInit(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //proj_player_init

        // Generate URL from route name
        $url = $router->generate('proj_player_init', ['num' => 1]);

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Project Black Jack: Player Creation', $crawler->filter('title')->text());
    }

    /**
     * testProjBlackJack
     *
     * Test the Black Jack game
     *
     * @return void
     */
    public function testProjBlackJack(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');


        $this->testProjBlackJackInit($client, $router);

        $this->testProjBlackJackGame($client, $router);

        $this->testProjBlackJackBet($client, $router);

        $this->testProjBlackJackDoubleDown($client, $router);

        $this->testProjBlackJackHit($client, $router);

        $this->testProjBlackJackStay($client, $router);

        $this->testProjBlackJackNewGame($client, $router);
    }
}
