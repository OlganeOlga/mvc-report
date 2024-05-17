<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests for LuckyController
 */
class LuckyControllerTest extends WebTestCase
{
    /**
     * Tests startpage /
     */
    public function testMeController(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Det är jag');
    }

    /**
     * Tests page about /about name 'about'
     */
    public function testAboutController(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Om kurs MVC');
    }

    /**
     * Tests route 'report'
     */
    public function testReportController(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/report');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Reportsidan för kurs MVC');
    }

    /**
     * Tests route 'lucky'
     */
    public function testLuckyController(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lucky');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Lycklig Sida');
    }
     /**
     * Tests route 'metrcs'
     */
     public function testMetricsController(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/metrics');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Metrics');
    }
}
