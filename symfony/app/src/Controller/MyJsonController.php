<?php

namespace App\Controller;

use App\CustomAnnotation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class MyJsonController extends AbstractController
{
    public $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    #[Route('/api', name: "api")]
    #[Annotation("This route shows all routes with path and their functions.")]
    public function apiIndex(Request $request)
    {
        // $allAttributes = $request->attributes->all();
        // $routeName = $request->attributes->get('_route');
        // $routeParameters = $request->attributes->get('_route_params');
        // var_dump($allAttributes);
        $routes = $this->router->getRouteCollection()->all();
        $data = [];

        foreach ($routes as $routeName => $route) {
            if ($routeName == "api") {
                $annotation = $request->attributes->get('_route_params');
                var_dump($annotation);
            }
            // Get custom annotation from route options
            $annotation = $request->attributes->get('_route_params')['customAnnotation'] ?? null;

            // Populate data array
            $data[$routeName] = [
                'name' => $routeName,
                'path' => $route->getPath(),
                'controller' => $route->getDefault('_controller'),
                'custom_annotation' => $annotation,
            ];
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
