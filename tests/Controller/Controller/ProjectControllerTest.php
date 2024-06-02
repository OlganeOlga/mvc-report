<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    /**
     * Test projekt homepage
     * @return void
     */
    public function testProject(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/proj');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'MVC projekt: kortspel');
    }

    /**
     * Test projekt about page
     * @return void
     */
    public function testAboutProject(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/proj/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Om MVC projekt');
    }

    /**
     * Test page that shows random card
     * @return void
     */
    public function testGetOneCard(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/proj/oneCard');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'En kort');
    }
}
