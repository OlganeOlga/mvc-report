<?php

// src/Controller/DocsController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DockController extends AbstractController
{
    #[Route('/docs/{path}', name: 'docs', requirements: ['path' => '.+'])]
    public function index(string $path): Response
    {
        $docsDir = $this->getParameter('kernel.project_dir') . '/docs/';
        $filePath = $docsDir . $path;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist');
        }

        return new Response(file_get_contents($filePath), 200, [
            'Content-Type' => mime_content_type($filePath),
        ]);
    }
}
