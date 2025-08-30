<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/uploads/{filename}', name: 'file_uploads')]
    public function serveUpload(string $filename): Response
    {
        /** @var string $projectDir */
        $projectDir = $this->getParameter('kernel.project_dir');

        // Define the path to your uploads directory inside var
        $uploadsDir = $projectDir.'/var/uploads';

        // Sanitize filename to prevent directory traversal
        $safeFilename = basename($filename);

        // Complete path to the file
        $filePath = $uploadsDir.'/'.$safeFilename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found');
        }

        // Serve the file
        return $this->file($filePath);
    }
}
