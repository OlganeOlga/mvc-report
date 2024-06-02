<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository; 

class ProductControllerTest extends WebTestCase
{
    /**
     * Test stertpage for Product
     * 
     * @return void
     */
    public function testProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello ProductController!');
    
    }

    /**
     * Test create product
     * 
     * @return void
     */
    public function testCreateProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Saved new product with id', $client->getResponse()->getContent());
        
        $container = self::$kernel->getContainer();
        $productRepository = $container->get('doctrine')->getRepository(\App\Entity\Product::class);
        
        // Get the last created product
        $product = $productRepository->findOneBy([], ['id' => 'DESC']);

        // Assert that the product is not null and has expected properties
        $this->assertNotNull($product);
        $this->assertStringContainsString('Keyboard_num_', $product->getName());
        $this->assertGreaterThanOrEqual(100, $product->getValue());
        $this->assertLessThanOrEqual(999, $product->getValue());
    }

    /**
     * Test reouter thet shows all products in the table
     * 
     * @return void
     */

    public function testShowAllProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/show');

        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Test show product by ID
     * @return void
     */
    public function testShowProductByID(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/show/2');

        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
    }

    /**
     * Test delete product by ID
     * @return void
     */
    public function testDeleteProductByIDProductNotExist(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/delete/1');

        $response = $client->getResponse();
        $statusCode = $response->getStatusCode();
        $this->assertEquals(404, $statusCode);
        $this->assertStringContainsString('No product found for id 1', $response->getContent());
    }

    /**
     * Test update products value by ID
     * @return void
     */
    public function testupdateProductByIDProductNotExist(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/update/1/40');

        $response = $client->getResponse();
        $statusCode = $response->getStatusCode();
        
        $this->assertEquals(404, $statusCode);
        $this->assertStringContainsString('No product found for id 1', $response->getContent());
    }

    /**
     * Test view all products in html
     * 
     * @return void
     */
    public function testViewAllProduct(): void
    {
        $client = static::createClient();
        $client->request('GET', '/product/view');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Visa produkter i html');
    }

    /**
     * Test view all products in html
     * 
     * @return void
     */
}
