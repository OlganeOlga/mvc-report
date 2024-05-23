<?php

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepositoryTest extends KernelTestCase
{
    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $doctrine;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var ?\Doctrine\ORM\EntityManager
     */
    private ?\Doctrine\ORM\EntityManager $entityManager = null;

    /**
     * Setup for class Product
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->doctrine = $kernel->getContainer()->get('doctrine');
        $this->entityManager = $this->doctrine->getManager();
        $this->productRepository = $this->entityManager->getRepository(Product::class);
    }

    /**
     * Test create product
     */
    public function testConstruct(): void
    {
        $product = new Product();
        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * Test find by minimum value
     */
    public function testFindByMinimumValue(): void
    {
        $value = 460;
        $products = $this->productRepository->findByMinimumValue($value);

        $this->assertIsArray($products);

        foreach ($products as $product) {
            $this->assertInstanceOf(Product::class, $product);
            $this->assertGreaterThanOrEqual($value, $product->getValue());
        }
    }

    /**
     * Test find by minimum value variant 2
     */
    public function testFindByMinimumValue2(): void
    {
        $value = 500;

        // Assuming you have already populated the database with products
        $products = $this->productRepository->findByMinimumValue2($value);

        // Ensure the method returns an array
        $this->assertIsArray($products);

        // Check if the returned array is not empty
        $this->assertNotEmpty($products);

        // Validate that all products have a value greater than or equal to the specified value
        foreach ($products as $product) {
            $this->assertGreaterThanOrEqual($value, $product['value']);
        }
    }

    /**
     * Tear down
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // Avoid memory leaks
    }
}
