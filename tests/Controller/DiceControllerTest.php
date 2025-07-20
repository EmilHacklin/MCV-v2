<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class DiceControllerTest extends WebTestCase
{
    /**
     * testPigInitPOST
     *
     * Test pig_init_post route
     * Is only private because it runs twice and don't need to be run three times also if you do the testPigRoll don't test everything
     *
     * @param KernelBrowser $client
     * @return void
     */
    private function testPigInitPOST(KernelBrowser $client): void
    {
        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('pig_init_post');

        // Prepare POST data
        $postData = [
            'num_dices' => 3,
        ];

        // Send POST request to the route
        $client->request('POST', $url, $postData);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testPigStart
     *
     * Test pig_start route
     *
     * @return void
     */
    public function testPigStart(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('pig_start');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Pig Game:', $crawler->filter('title')->text());
    }

    /**
     * testPigInitGET
     *
     * Test pig_init_get route
     *
     * @return void
     */
    public function testPigInitGET(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('pig_init_get');

        // Send a GET request to the route you want to test
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Pig Game: init', $crawler->filter('title')->text());
    }

    /**
     * testPigPlay
     *
     * Test pig_play route
     *
     * @return void
     */
    public function testPigPlay(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //Needed or the server code becomes 500
        $this->testPigInitPOST($client);

        // Generate URL from route name
        $url = $router->generate('pig_play');

        // Send a GET request to the route you want to test
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Pig Game: Play', $crawler->filter('title')->text());
    }

    /**
     * testPigSave
     *
     * Test pig_save route
     *
     * @return void
     */
    public function testPigSave(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('pig_save');

        // Send POST request to the route
        $client->request('POST', $url);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    /**
     * testPigRoll
     *
     * Test pig_roll route
     *
     * @return void
     */
    public function testPigRoll(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        //Needed or the server code becomes 500
        $this->testPigInitPOST($client);

        // Generate URL from route name
        $url = $router->generate('pig_roll');

        // Send POST request to the route
        for ($i = 0; $i < 5; $i++) {
            $client->request('POST', $url);

            // Assert that response is a redirect (302 status)
            $response = $client->getResponse();
            $this->assertTrue($response->isRedirect());
        }
    }

    /**
     * testRollDice
     *
     * Test test_roll_dice route
     *
     * @return void
     */
    public function testRollDice(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('test_roll_dice');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Pig Game: Test: Roll', $crawler->filter('title')->text());
    }

    /**
     * testRollNumDice
     *
     * Test test_roll_num_dices route
     *
     * @return void
     */
    public function testRollNumDice(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('test_roll_num_dices', ['num' => 3]);

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Pig Game: Test: Roll', $crawler->filter('title')->text());
    }

    /**
     * testDicehand
     *
     * Test test_dicehand route
     *
     * @return void
     */
    public function testDicehand(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('test_dicehand', ['num' => 3]);

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Pig Game: Test: Dicehand', $crawler->filter('title')->text());
    }
}
