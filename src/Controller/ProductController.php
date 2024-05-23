<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;

/**
 * Controllers for exercise Product
 */
class ProductController extends AbstractController
{
    /**
     * @var ManagerRegistry $doctrine
     */
    private $doctrine;

     /**
     * @var ProductRepository $productRepository
     */
    private $productRepository;

    /**
     * Constructor
     */
    public function __construct(ManagerRegistry $doctrine, ProductRepository $productRepository)
    {
        $this->doctrine = $doctrine;
        $this->productRepository = $productRepository;
    }

    /**
     * @return Response
     */
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * Creates new products with standart value and name in the database
     * 
     * @return Response
     */
    #[Route('/product/create', name: 'product_create')]
    public function createProduct(): Response {
        $entityManager = $this->doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard_num_' . rand(1, 9));
        $product->setValue(rand(100, 999));

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * Shows all products in the database
     * 
     * @return Response
     */
    #[Route('/product/show', name: 'product_show_all')]
    public function showAllProduct(): Response
    {
        $products = $this->productRepository
            ->findAll();

        $response = $this->json($products);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Shows product from database with given product id
     *
     * @param int $prodId,
     * @return Response
     */
    #[Route('/product/show/{prodId}', name: 'product_by_id')]
    public function showProductById(
        int $prodId
    ): Response {
        $product = $this->productRepository
            ->find($prodId);

        return $this->json($product);
    }

    /**
     * Deletes product with the prodId from the database
     *
     * @param int $prodId,
     * @return Response
     */
    #[Route('/product/delete/{prodId}', name: 'product_delete_by_id')]
    public function deleteProductById(
        int $prodId
    ): Response {
        $entityManager = $this->doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($prodId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$prodId
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_show_all');
    }

    /**
     * Updates product with the prodId in the database
     * set value as given
     * 
     * @param int $prodId,
     * @param int $value
     * @return Response
     */
    #[Route('/product/update/{prodId}/{value}', name: 'product_update')]
    public function updateProduct(
        int $prodId,
        int $value
    ): Response {
        $entityManager = $this->doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($prodId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$prodId
            );
        }

        $product->setValue($value);
        $entityManager->flush();

        return $this->redirectToRoute('product_show_all');
    }

    /**
     * Shows products in the table product
     *
     * @return Response
     */
    #[Route('/product/view', name: 'product_view_all')]
    public function viewAllProduct(): Response
    {
        $products = $this->productRepository->findAll();

        $data = [
            'products' => $products
        ];

        return $this->render('product/view.html.twig', $data);
    }

    /**
     * Test function findByMinimumValue
     * 
     * @return Response
     */
    #[Route('/product/view/{value}', name: 'product_view_minimum_value')]
    public function viewProductWithMinimumValue(
        int $value
    ): Response {
        $products = $this->productRepository->findByMinimumValue($value);

        $data = [
            'products' => $products
        ];

        return $this->render('product/view.html.twig', $data);
    }

    /**
     * Test function findByMinimumValue2
     * 
     * @return Response
     */
    #[Route('/product/show/min/{value}', name: 'product_by_min_value')]
    public function showProductByMinimumValue(
        int $value
    ): Response {
        $products = $this->productRepository->findByMinimumValue2($value);

        return $this->json($products);
    }
}
