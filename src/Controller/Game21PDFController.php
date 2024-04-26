<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Game21\CardGraphics;
use App\Game21\Desk;
use App\Game21\Hand;
use App\Game21\Player;
use App\Game21\Bank;

class Game21PDFController extends AbstractController
{
    #[Route("/game/doc", name: "doc21")]
    public function viewDoc(): Response
    {
        return $this->render('game21/doc/doc.html.twig');
    }

    #[Route("/game/Flodesschema", name: "docFlodeschema")] //Show flödersschema for game21
    public function viewPdf(KernelInterface $kernel): Response
    {
        $pdfPath = $kernel->getProjectDir() . '/public/pdf/Flodesschema.drawio.pdf';
        $pdfContent = file_get_contents($pdfPath);

        if ($pdfContent !== false) {
            $response = new Response($pdfContent);
        
            // Set the Content-Type header to indicate it's a PDF file
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="Flodesschema.drawio.pdf"');
        
            return $response;
        } else {
            throw $this->createNotFoundException('The PDF file could not be found.');
        }
    }

    #[Route("/game/Classes", name: "docClass")] //Show classses for game 21
    public function viewClass(KernelInterface $kernel): Response
    {
        $pdfPath = $kernel->getProjectDir() . '/public/pdf/classes.pdf';
        $pdfContent = file_get_contents($pdfPath);

        if ($pdfContent !== false) {
            $response = new Response($pdfContent);
        
            // Set the Content-Type header to indicate it's a PDF file
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="Flodesschema.drawio.pdf"');
        
            return $response;
        } else {
            throw $this->createNotFoundException('The PDF file could not be found.');
        }
    }

    #[Route("/game/pseudocode", name: "docPseudo")] //show pseudocode for Game21
    public function viewPseudo(KernelInterface $kernel): Response
    {
        $pdfPath = $kernel->getProjectDir() . '/public/pdf/pseudocode.pdf';
        $pdfContent = file_get_contents($pdfPath);

        if ($pdfContent !== false) {
            $response = new Response($pdfContent);
        
            // Set the Content-Type header to indicate it's a PDF file
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="Flodesschema.drawio.pdf"');
        
            return $response;
        } else {
            throw $this->createNotFoundException('The PDF file could not be found.');
        }
    }
}