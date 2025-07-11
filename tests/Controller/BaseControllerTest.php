<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class BaseControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    /**
     * setUp
     *
     * Add client startup to setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp(); // Call parent setup

        // Create a client to browse the application
        $this->client = static::createClient();
    }

    /**
     * testHome
     *
     * Test home route
     *
     * @return void
     */
    public function testHome(): void
    {
        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //home

        // Generate URL from route name
        $url = $router->generate('home');

        // Send a GET request to the route you want to test
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('MVC: Home', $crawler->filter('title')->text());
    }

    /**
     * testAbout
     *
     * Test about route
     *
     * @return void
     */
    public function testAbout(): void
    {
        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //about

        // Generate URL from route name
        $url = $router->generate('about');

        // Send a GET request to the route you want to test
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('MVC: About', $crawler->filter('title')->text());
    }

    /**
     * testReport
     *
     * Test report route
     *
     * @return void
     */
    public function testReport(): void
    {
        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //report

        // Generate URL from route name
        $url = $router->generate('report');

        // Send a GET request to the route you want to test
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('MVC: Report', $crawler->filter('title')->text());
    }

    /**
     * testLucky
     *
     * Test lucky route
     *
     * @return void
     */
    public function testLucky(): void
    {
        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //lucky

        // Generate URL from route name
        $url = $router->generate('lucky');

        // Send a GET request to the route you want to test
        $crawler = $this->client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('MVC: Lucky', $crawler->filter('title')->text());
    }
}
