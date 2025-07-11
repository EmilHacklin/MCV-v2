<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Routing\RouterInterface;

class DiceGameControllerExceptionTest extends WebTestCase
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
     * testDiceLowException
     *
     * Test the exception handling for higher bound
     *
     * @return void
     */
    public function testDiceHighException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('test_roll_num_dices', ['num' => 100]);

        // Error message to look for
        $errorMessage = "Can't roll more than 99 dices!";

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testDiceLowException
     *
     * Test the exception handling for lower bound
     *
     * @return void
     */
    public function testDiceLowException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('test_roll_num_dices', ['num' => 0]);

        // Error message to look for
        $errorMessage = "Can't roll less than 1 die!";

        // Disable exception catching
        $client->catchExceptions(false);

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testDicehandHighException
     *
     * Test the exception handling for higher bound
     *
     * @return void
     */
    public function testDicehandHighException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('test_dicehand', ['num' => 100]);

        // Error message to look for
        $errorMessage = "Can't roll more than 99 dices!";

        $this->testException($client, $url, $errorMessage);
    }

    /**
     * testDicehandLowException
     *
     * Test the exception handling for lower bound
     *
     * @return void
     */
    public function testDicehandLowException(): void
    {
        // Create a client to browse the application
        $client = static::createClient();

        // Retrieve router service
        /** @var RouterInterface $router */
        $router = static::getContainer()->get('router');

        // Generate URL from route name
        $url = $router->generate('test_dicehand', ['num' => 0]);

        // Error message to look for
        $errorMessage = "Can't roll less than 1 die!";

        // Disable exception catching
        $client->catchExceptions(false);

        $this->testException($client, $url, $errorMessage);
    }
}
