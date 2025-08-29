<?php

namespace App;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadManager
{
    private string $targetPath;
    private string $targetDirectory;
    private string $targetDirectoryPath;

    /**
     * __construct.
     *
     * @return void
     */
    public function __construct(string $targetPath, string $targetDirectory = '/uploads')
    {
        $this->targetPath = $targetPath;
        $this->targetDirectory = ltrim($targetDirectory, '/');
        $this->targetDirectoryPath = rtrim($this->targetPath.$this->targetDirectory, '/');

        // Make sure the directory exists
        if (!is_dir($this->targetDirectoryPath)) {
            mkdir($this->targetDirectoryPath, 0777, true);
        }
        // Check write permissions
        if (!is_writable($this->targetDirectoryPath)) {
            throw new \RuntimeException($this->targetDirectory.' directory is not writable.');
        }
    }

    /**
     * getTargetPath.
     */
    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    /**
     * getTargetDirectory.
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * getTargetDirectoryPath.
     */
    public function getTargetDirectoryPath(): string
    {
        return $this->targetDirectoryPath;
    }

    /**
     * saveUploadedFile.
     *
     * Saves the uploaded file to the targetPath/targetFolder and returns the path
     *
     * @param array<string> $fileTypesSupported
     *
     * @return string|null string containing filePath if successful null if failed
     */
    public function saveUploadedFile(?UploadedFile $file, array $fileTypesSupported): ?string
    {
        $filePath = null;

        if ($file instanceof UploadedFile && $file->isValid()) {
            // Check if file is of allowed type
            $fileType = $file->getMimeType();
            if (!in_array($fileType, $fileTypesSupported)) {
                throw new \RuntimeException('Invalid file type. Filetype of '.$fileType.' is not allowed.');
            }

            /** @var string $originalFilename */
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            // Move the file to the directory
            try {
                $file->move($this->targetDirectoryPath, $newFilename);
                // Save $newFilename and path to database
                $filePath = '/'.$this->targetDirectory.'/'.$newFilename;
            } catch (\Exception $e) {
                throw new \RuntimeException('Error uploading file: '.$e->getMessage());
            }
        }

        return $filePath;
    }

    /**
     * deleteUploadedFile.
     *
     * Deletes the file at the given path within the target directory.
     *
     * @param string $filePath The relative path to the image, e.g., '/uploads/filename.jpg'
     *
     * @return bool True if deletion was successful, false otherwise
     */
    public function deleteUploadedFile(string $filePath): bool
    {
        // Full path to the file
        $fullPath = $this->targetPath.$filePath;

        // Check if file exists
        if (!file_exists($fullPath)) {
            // File does not exist
            return false;
        }

        // Attempt to delete the file
        return unlink($fullPath);
    }
}
