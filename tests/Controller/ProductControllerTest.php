<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class ProductControllerTest extends WebTestCase
{
    private static ?int $testId = null;

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
     * testAppProduct
     *
     * Test app_product route
     *
     * @return void
     */
    public function testAppProduct(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('app_product');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Hello ProductController!', $crawler->filter('title')->text());
    }

    /**
     * testAppProductCreate
     *
     * Test product_create route
     *
     * @return void
     */
    public function testAppProductCreate(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('product_create');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert response status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Set up testing variable
        self::$testId = intval(substr($crawler->text(), 26));
    }

    /**
     * testAppProductShow
     *
     * Test product_show_all route
     *
     * @return void
     */
    public function testAppProductShow(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('product_show_all');

        // Send a GET request to the route
        $client->request('GET', $url);

        // Assert response status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * testAppProductShowId
     *
     * Test product_by_id route
     *
     * @depends testAppProductCreate
     *
     * @return void
     */
    public function testAppProductShowId(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('product_by_id', ['id' => self::$testId]);

        // Send a GET request to the route
        $client->request('GET', $url);

        // Assert response status
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * testAppProductUpdateAndDelete
     *
     * Test product_delete_by_id and product_update route
     *
     * @depends testAppProductCreate
     *
     * @return void
     */
    public function testAppProductUpdateAndDelete(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $urlUpdate = $router->generate('product_update', ['id' => self::$testId, 'value' => 69]);

        // Generate URL from route name
        $urlDelete = $router->generate('product_delete_by_id', ['id' => self::$testId]);

        // Send a GET request to the route
        $client->request('GET', $urlUpdate);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());

        // Send a GET request to the route
        $client->request('GET', $urlDelete);

        // Assert that response is a redirect (302 status)
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect());

        // Error message to look for
        $errorMessageUpdate = "No product found for id " . self::$testId;

        // Error message to look for
        $errorMessageDelete = "No product found for id " . self::$testId;

        $this->testException($client, $urlUpdate, $errorMessageUpdate);

        $this->testException($client, $urlDelete, $errorMessageDelete);
    }

    /**
     * testAppProductUpdateException
     *
     * Test product_update route exception handling
     *
     * @depends testAppProductUpdateAndDelete
     *
     * @return void
     */
    public function testAppProductUpdateException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('product_update', ['id' => self::$testId, 'value' => 69]);

        // Error message to look for
        $errorMessage = "No product found for id " . self::$testId;

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testAppProductDeleteException
     *
     * Test product_delete_by_id route exception handling
     *
     * @depends testAppProductUpdateAndDelete
     *
     * @return void
     */
    public function testAppProductDeleteException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('product_delete_by_id', ['id' => self::$testId]);

        // Error message to look for
        $errorMessage = "No product found for id " . self::$testId;

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testAppProductView
     *
     * Test product_view_all route
     *
     * @return void
     */
    public function testAppProductView(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('product_view_all');

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Hello ProductController!', $crawler->filter('title')->text());
    }

    /**
     * testAppProductViewWithMinimum
     *
     * Test product_view_minimum_value route
     *
     * @return void
     */
    public function testAppProductViewWithMinimum(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('product_view_minimum_value', ['value' => 5]);

        // Send a GET request to the route
        $crawler = $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that certain content exists in the response
        $this->assertStringContainsString('Hello ProductController!', $crawler->filter('title')->text());
    }

    /**
     * testAppProductViewByMinimum
     *
     * Test product_by_min_value route
     *
     * @return void
     */
    public function testAppProductViewByMinimum(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('product_by_min_value', ['value' => 5]);

        // Send a GET request to the route
        $client->request('GET', $url);

        // Assert the response status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
