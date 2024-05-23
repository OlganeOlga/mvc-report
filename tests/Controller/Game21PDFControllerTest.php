<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class Game21PDFControllerTest extends WebTestCase
{
    /**
     * Test page with documentation
     * 
     * @return void
     */
    public function testViewDoc(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/doc');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Game 21 dokumentation');
    }

    /**
     * Test if PDF with Floderschema exists
     * 
     * @return void
     */
    public function testViewPdfFileExists(): void
    {
        // Create a client to simulate a browser
        $client = static::createClient();

        // Mock the kernel to use a custom project directory
        $kernel = $client->getKernel();
        $projectDir = $kernel->getProjectDir();
        
        // Ensure the PDF file exists for the test
        $pdfPath = $projectDir . '/public/pdf/Flodesschema.drawio.pdf';
        file_put_contents($pdfPath, 'dummy content');

        // Request the route
        $client->request('GET', '/game/Flodesschema');

        // Get the response
        $response = $client->getResponse();

        // Assert the response is successful and is a PDF file
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('inline; filename="Flodesschema.drawio.pdf"', $response->headers->get('Content-Disposition'));

        // Clean up the dummy PDF file
        unlink($pdfPath);
    }

    /**
     * Test if the PDF with Floderschema does not exosts
     * 
     * @return void
     * 
     */
    public function testViewPdfFileNotExists(): void
    {
        // Create a client to simulate a browser
        $client = static::createClient();

        // Mock the kernel to use a custom project directory
        $kernel = $client->getKernel();
        $projectDir = $kernel->getProjectDir();
        
        // Ensure the PDF file does not exist for the test
        $pdfPath = $projectDir . '/public/pdf/Flodeschema.drawio.pdf';
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        // Request the route
        $client->request('GET', '/game/Flodesschema');

        // Get the response
        $response = $client->getResponse();

        // Assert the response is a 404 Not Found
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * Test if file with classes exists
     * 
     * @return void
     * 
     */
    public function testViewClasses(): void
    {
        $client = static::createClient();

        $kernel = $client->getKernel();
        $projectDir = $kernel->getProjectDir();

        $classPath = $projectDir . '/public/pdf/classes.pdf';
        file_put_contents($classPath, 'dummy content');

        $client->request('GET', '/game/Classes');
        $response = $client->getResponse();

        // Assert the response is successful and is a PDF file
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('inline; filename="classes.pdf"', $response->headers->get('Content-Disposition'));

        // Clean up the dummy PDF file
        unlink($classPath);
    }

    /**
     * Test if file with classes doenotexists
     * 
     * @return void
     */
    public function testViewClassesNotFound(): void
    {
        $client = static::createClient();

        $kernel = $client->getKernel();
        $projectDir = $kernel->getProjectDir();

        $classPath = $projectDir . '/public/pdf/classe.pdf';
        if (file_exists($classPath)) {
            unlink($classPath);
        }
        file_put_contents($classPath, 'dummy content');

        $client->request('GET', '/game/Classes');
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * Test if file with pseudokode will be shown
     * 
     * @return void
     */
    public function testViewPseudocodeExist(): void
    {
        $client = static::createClient();

        $kernel = $client->getKernel();
        $projectDir = $kernel->getProjectDir();

        $pseudoPath = $projectDir . '/public/pdf/pseudocode.pdf';
        file_put_contents($pseudoPath, 'dummy content');

        $client->request('GET', '/game/pseudocode');
        $response = $client->getResponse();

        // Assert the response is successful and is a PDF file
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('inline; filename="pseudocode.pdf"', $response->headers->get('Content-Disposition'));

        // Clean up the dummy PDF file
        unlink($pseudoPath);
    }

    /**
     * Test if file with pseudocode doenot exists
     * 
     * @return void
     */
    public function testViewPsseudocodeNotFound(): void
    {
        $client = static::createClient();

        $kernel = $client->getKernel();
        $projectDir = $kernel->getProjectDir();

        $pseudoPath = $projectDir . '/public/pdf/pseudo.pdf';
        if (file_exists($pseudoPath)) {
            unlink($pseudoPath);
        }
        file_put_contents($pseudoPath, 'dummy content');

        $client->request('GET', '/game/pseudocodes');
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
