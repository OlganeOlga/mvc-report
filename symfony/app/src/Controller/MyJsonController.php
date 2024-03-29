<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

//class ApiController extends AbstractController
class MyJsonController extends AbstractController
{
    public $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    #[Route('/api', name: "api")]
    public function apiIndex()
    {

        // $data = [
        //     'name' => 'Json',
        //     'function' => 'Routers',
        // ];
        $routes = $this->router->getRouteCollection()->all();

        $data = [];
        foreach ($routes as $routeName => $route) {
            $data[$routeName] = [$routeName, $route->getPath(), $route->getDefault('_controller')];
        }

        return $this->render('api.html.twig', ['data' => $data]);
    }

    #[Route('/api/quote', name: "api.quote")]
    public function geQuote(KernelInterface $kernel): Response
    {
        $jsonFile = $kernel->getProjectDir() . '/public/content/quote.json';
        $jsonContent =  file_get_contents($jsonFile);
        $data = json_decode($jsonContent, true);

        $quoteIndex = random_int(0, count($data) - 1);
        $randomQuote = $data[$quoteIndex];


        return $this->render('api_quote.html.twig', $randomQuote);
    }
}
