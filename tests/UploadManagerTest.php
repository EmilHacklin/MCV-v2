<?php

namespace App\Tests;

use App\UploadManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadManagerTest extends WebTestCase
{
    private UploadManager $uploadManager;
    private string $testDir;

    /**
     * setUp
     *
     * Add client startup to setup
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp(); // Call parent setup

        // Setup a temporary directory for testing
        $this->testDir = __DIR__ . '/_temp/upload_manager_test';

        $this->testDir = rtrim($this->testDir, '/');

        if (!is_dir($this->testDir)) {
            mkdir($this->testDir, 0777, true);
        }

        // Instantiate UploadManager with test directory
        $this->uploadManager = new UploadManager($this->testDir);
    }

    /**
     * tearDown
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->deleteDirectory($this->testDir);

        parent::tearDown();
    }

    /**
     * deleteDirectory
     *
     * Recursively delete a directory and its contents
     *
     * @param  string $dir
     * @return void
     */
    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;
            (is_dir($filePath)) ? $this->deleteDirectory($filePath) : unlink($filePath);
        }

        rmdir($dir);
    }

    /**
     * testCreateObject
     *
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     *
     * @return void
     */
    public function testCreateObject(): void
    {
        $uploadManager = new UploadManager($this->testDir);
        $this->assertInstanceOf(UploadManager::class, $uploadManager);
    }

    /**
     * testConstructorException
     *
     * @return void
     */
    public function testConstructorException(): void
    {
        // If the dir don't exist
        if (!is_dir($this->testDir . "/public/notwritable")) {
            mkdir($this->testDir . "/public/notwritable", 0777, true);
        }

        // Change permissions to make it non-writable
        chmod($this->testDir . "/public/notwritable", 0555); // read and execute only

        // Expect RuntimeException
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('directory is not writable.');

        // Instantiate UploadManager, should throw exception
        new UploadManager($this->testDir, '/notwritable');

        // Reset permissions for cleanup
        chmod($this->testDir . "/public/notwritable", 0777);
    }

    /**
     * testSaveUploadedFile
     *
     * @return void
     */
    public function testSaveUploadedFile(): void
    {
        // Create a mock UploadedFile
        $file = $this->createMock(UploadedFile::class);
        $file->method('isValid')->willReturn(true);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientOriginalName')->willReturn('test_image.jpg');
        $file->method('guessExtension')->willReturn('jpg');

        $targetDir = 'public/uploads'; // no leading slash
        $uploadDir = $this->testDir . '/' . $targetDir; // should be safe

        // Escape directory string for regex
        $escapedDir = '/' . preg_quote($uploadDir, '/') . '/';

        // Mock move method
        $file->expects($this->once())
            ->method('move')
            ->with(
                $this->matchesRegularExpression($escapedDir),
                $this->callback(function ($filename) {
                    return preg_match('/^test_image-.*\.jpg$/', $filename) === 1;
                })
            );

        $fileTypesSupported = ['image/jpeg', 'image/png'];

        $result = $this->uploadManager->saveUploadedFile($file, $fileTypesSupported);

        $this->assertNotNull($result);
        $this->assertStringContainsString('/uploads/', /** @scrutinizer ignore-type */ $result);
    }

    /**
     * testSaveUploadedFileInvalidFileType
     *
     * @return void
     */
    public function testSaveUploadedFileInvalidFileType(): void
    {
        // Create a mock UploadedFile
        $file = $this->createMock(UploadedFile::class);
        $file->method('isValid')->willReturn(true);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientOriginalName')->willReturn('test.jpg');
        $file->method('guessExtension')->willReturn('jpg');

        $fileTypesSupported = ['image/png'];

        // Expect a RuntimeException for invalid file type
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid file type. Filetype of image/jpeg is not allowed.');

        // Call the method - should throw exception
        $this->uploadManager->saveUploadedFile($file, $fileTypesSupported);
    }

    /**
     * testSaveUploadedFileMoveException
     *
     * @return void
     */
    public function testSaveUploadedFileMoveException(): void
    {
        // Create a mock UploadedFile
        $file = $this->createMock(UploadedFile::class);
        $file->method('isValid')->willReturn(true);
        $file->method('getMimeType')->willReturn('image/jpeg');
        $file->method('getClientOriginalName')->willReturn('test.jpg');
        $file->method('guessExtension')->willReturn('jpg');

        // Simulate exception during move
        $file->expects($this->once())
             ->method('move')
             ->will($this->throwException(new \Exception('Move failed')));

        $fileTypesSupported = ['image/jpeg'];

        // Expect a RuntimeException with specific message
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error uploading file: Move failed');

        $this->uploadManager->saveUploadedFile($file, $fileTypesSupported);
    }

    /**
     * testDeleteUploadedFile
     *
     * @return void
     */
    public function testDeleteUploadedFile(): void
    {
        // Create a dummy file
        $filePath = '/uploads/testfile.txt';
        $fullPath = $this->testDir . '/public' . $filePath;
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }
        file_put_contents($fullPath, 'test');

        $result = $this->uploadManager->deleteUploadedFile($filePath);

        $this->assertTrue($result);
        $this->assertFalse(file_exists($fullPath));

        // Test if the file don't exists
        $result = $this->uploadManager->deleteUploadedFile($filePath);

        $this->assertFalse($result);
    }
}
