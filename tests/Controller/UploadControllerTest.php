<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class UploadControllerTest extends WebTestCase
{
    private string $uploadsDir;
    private KernelBrowser $client;

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Use static::createClient() to initialize the client and boot the kernel
        $this->client = static::createClient();

        // Access the container via the client
        $kernel = $this->client->getKernel();

        // Get the project directory parameter
        $projectDir = $kernel->getContainer()->getParameter('kernel.project_dir');

        if (!is_string($projectDir)) {
            throw new \RuntimeException('Could not get kernel.project_dir parameter.');
        }

        $this->uploadsDir = $projectDir . '/var/uploads';

        if (!is_dir($this->uploadsDir)) {
            mkdir($this->uploadsDir, 0777, true);
        }

        // Create a dummy file for testing
        file_put_contents($this->uploadsDir . '/testfile.txt', 'Test content');
    }

    /**
     * tearDown
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // Remove test file
        $testFile = $this->uploadsDir . '/testfile.txt';
        if (file_exists($testFile)) {
            unlink($testFile);
        }
        parent::tearDown();
    }

    /**
     * testServeUploadFileExists
     *
     * @return void
     */
    public function testServeUploadFileExists(): void
    {
        $this->client->request('GET', '/uploads/testfile.txt');

        $response = $this->client->getResponse();

        // Assert status code first
        $this->assertEquals(200, $response->getStatusCode());

        // Get Content-Type header directly
        $contentType = $response->headers->get('Content-Type');

        // Assert that Content-Type is present and contains 'text/plain'
        $this->assertIsString($contentType);
        $this->assertStringContainsString('text/plain', $contentType);
    }

    /**
     * testServeUploadFileNotFound
     *
     * @return void
     */
    public function testServeUploadFileNotFound(): void
    {

        $this->client->request('GET', '/uploads/nonexistent.txt');

        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());

        $content = $response->getContent();
        // Assert content is a string before asserting substring
        $this->assertIsString($content);
        $this->assertStringContainsString('Not Found', $content);
    }
}
