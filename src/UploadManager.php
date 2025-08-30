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
        $this->targetDirectoryPath = rtrim($this->targetPath.'/'.$this->targetDirectory, '/');

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
     * sanitizeFilename.
     */
    private function sanitizeFilename(string $filename): string
    {
        // Replace all non-alphanumeric characters with underscores
        /** @var string $sanitized */
        $sanitized = preg_replace('/[^A-Za-z0-9_]/', '_', $filename);

        // Convert to lowercase
        return strtolower($sanitized);
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
     * @return string|null string containing filename if successful null if failed
     */
    public function saveUploadedFile(?UploadedFile $file, array $fileTypesSupported): ?string
    {
        $newFilename = null;

        if ($file instanceof UploadedFile && $file->isValid()) {
            // Check if file is of allowed type
            $fileType = $file->getMimeType();
            if (!in_array($fileType, $fileTypesSupported)) {
                throw new \RuntimeException('Invalid file type. Filetype of '.$fileType.' is not allowed.');
            }

            /** @var string $originalFilename */
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->sanitizeFilename($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            // Move the file to the directory
            try {
                $file->move($this->targetDirectoryPath, $newFilename);
            } catch (\Exception $e) {
                throw new \RuntimeException('Error uploading file: '.$e->getMessage());
            }
        }

        return $newFilename;
    }

    /**
     * deleteUploadedFile.
     *
     * Deletes the file at the given path within the target directory.
     *
     * @param string $fileName The filename filename.jpg'
     *
     * @return bool True if deletion was successful, false otherwise
     */
    public function deleteUploadedFile(string $fileName): bool
    {
        // Full path to the file
        $fullPath = $this->targetDirectoryPath.'/'.$fileName;

        // Check if file exists
        if (!file_exists($fullPath)) {
            // File does not exist
            return false;
        }

        // Attempt to delete the file
        return unlink($fullPath);
    }
}
