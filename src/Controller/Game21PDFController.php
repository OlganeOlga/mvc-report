<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

/**
 * Shows documentation for te developeing CardPlay21
 */
class Game21PDFController extends AbstractController
{
    /**
     * Shows documents in PDF
     * 
     * @return Response
     */
    #[Route("/game/doc", name: "doc21")]
    public function viewDoc(): Response
    {
        return $this->render('game21/doc/doc.html.twig');
    }

    /**
     * Shows Flodersschema
     * 
     * @return Response
     */
    #[Route("/game/Flodesschema", name: "docFlodeschema")] //Show flödersschema for game21
    public function viewPdf(KernelInterface $kernel): Response
    {
        $pdfPath = $kernel->getProjectDir() . '/public/pdf/Flodesschema.drawio.pdf';

        if (file_exists($pdfPath)) {
            $response = new Response(file_get_contents($pdfPath));

            // Set the Content-Type header to indicate it's a PDF file
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="Flodesschema.drawio.pdf"');

            return $response;
        }

        throw $this->createNotFoundException('The PDF file could not be found.');

    }

    /**
     * Shows description of the classes
     * 
     * @return Response
     */
    #[Route("/game/Classes", name: "docClass")] //Show classses for game 21
    public function viewClass(KernelInterface $kernel): Response
    {
        $pdfPath = $kernel->getProjectDir() . '/public/pdf/classes.pdf';

        if (file_exists($pdfPath)) {
            $response = new Response(file_get_contents($pdfPath));

            // Set the Content-Type header to indicate it's a PDF file
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="classes.pdf"');

            return $response;
        }
        throw $this->createNotFoundException('The PDF file could not be found.');
    }

    /**
     * Shows pseudocode
     * 
     * @return Response
     */
    #[Route("/game/pseudocode", name: "docPseudo")] //show pseudocode for Game21
    public function viewPseudo(KernelInterface $kernel): Response
    {
        $pdfPath = $kernel->getProjectDir() . '/public/pdf/pseudocode.pdf';

        if (file_exists($pdfPath)) {
            $response = new Response(file_get_contents($pdfPath));

            // Set the Content-Type header to indicate it's a PDF file
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="pseudocode.pdf"');

            return $response;
        }
        throw $this->createNotFoundException('The PDF file could not be found.');
    }
}
