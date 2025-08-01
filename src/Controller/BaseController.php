<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/report', name: 'report')]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route('/lucky', name: 'lucky')]
    public function lucky(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number,
        ];

        return $this->render('lucky.html.twig', $data);
    }

    #[Route('/metrics', name: 'metrics')]
    public function metrics(): Response
    {
        return $this->render('metrics.html.twig');
    }

    #[Route('/docs/old_metrics/index', name: 'old_metrics')]
    public function oldMetrics(): Response
    {
        $projectDir = $this->getParameter('kernel.project_dir');

        if (!is_string($projectDir)) {
            throw new \RuntimeException('kernel.project_dir is not a string');
        }

        $filePath = $projectDir.'/public/docs/old_metrics/index.html';

        return new BinaryFileResponse($filePath);
    }

    #[Route('/docs/new_metrics/index', name: 'new_metrics')]
    public function newMetrics(): Response
    {
        $projectDir = $this->getParameter('kernel.project_dir');

        if (!is_string($projectDir)) {
            throw new \RuntimeException('kernel.project_dir is not a string');
        }

        $filePath = $projectDir.'/public/docs/new_metrics/index.html';

        return new BinaryFileResponse($filePath);
    }
}
