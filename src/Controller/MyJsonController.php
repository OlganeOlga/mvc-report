<?php

namespace App\Controller;

use App\Card\Desk;

use ReflectionClass;
use ReflectionMethod;
use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

//use App\Repository\BookRepository; // Import BookRepository

/**
 * Controller class that produces routers returning json objects
 */
class MyJsonController extends AbstractController
{
    /**
     * @var RouterInterface $router represents all routes in the application
     */
    public RouterInterface $router;

    /**
     * controller initiate public $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Router displays page with links to all json-routes
     * 
     * @return Response : redirect to the twig html template displaying links to all
     * jason-routes
     */
    #[Route('/json1', name: "api_landing")]
    public function apiLadning(): Response
    {
        //return new Response('Hello from the apiLadning() method!');
        return $this->render('myreport/json.html.twig');
    }

    /**
     * Router display all routes in the application in form of json
     * 
     * @param KernelInterface $kernel
     * @return Response : all routes in the application and their functions in form of json
     */
    #[Route('/api', name: "api")]
    public function apiIndex(KernelInterface $kernel): Response
    {
        $routes = $this->router->getRouteCollection()->all();
        $jsonFile = $kernel->getProjectDir() . '/public/json/routerannotation.json';
        $jsonContent =  file_get_contents($jsonFile);
        $annotations = json_decode($jsonContent, true);
        $data = [];
        $response = new Response();

        foreach ($routes as $routeName => $route) {

            if (substr($routeName, 0, 1) !== '_') {
                // Populate data array
                $data[$routeName] = [
                    'name' => $routeName,
                    'path' => $route->getPath(),
                    'controller' => $route->getDefault('_controller'),
                    'annotation' => $annotations[$routeName],
                ];
            }
        }
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Route display a rundome quote in form of json-content
     * @param KernelInterface $kernel
     * @return Response content with a rundome quote in form of json
     */
    #[Route('/api/quote', name: "api.quote")]
    public function geQuote(KernelInterface $kernel): Response
    {
        $jsonFile = $kernel->getProjectDir() . '/public/json/quote.json';
        $jsonContent =  file_get_contents($jsonFile);

        $response = new Response();

        $data = json_decode($jsonContent, true);

        $quoteIndex = random_int(0, count($data) - 1);
        $randomQuote = $data[$quoteIndex];
        $response->setContent(json_encode($randomQuote));

        return $response;
    }
}
