<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route('/', name: "me")]
    public function me(): Response
    {
        return $this->render('me.html.twig');
    }

    #[Route('/about', name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/report', name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route('/lucky', name: "lucky")]
    public function lucky(): Response
    {
        $number = random_int(0, 100);
        $img = random_int(0, 10) + 1;

        $data = [
            'number' => $number,
            'message' => 'Just nu lycklig nummer är: ',
            'image' => 'img/img/calender/lucky' . $img . '.jpg',
        ];

        // $response = new JsonResponse($data);
        // $response->setEncodingOptions(
        //     $response->getEncodingOptions() | JSON_PRETTY_PRINT
        // );
        return $this->render('lucky.html.twig', $data);
    }

    // #[Route('/api', name: "api")]
    // public function apiIndex()
    // {

    //     $data = [
    //         'name' => 'Json',
    //         'function' => 'Routers',
    //     ];
    //     //$routes = $this->router->getRouteCollection()->all();

    //     // $data = [];
    //     // foreach ($routes as $routeName => $route) {
    //     //     if (strpos($route->getDefault('_controller'), 'json') !== false) {
    //     //         $data[$routeName] = $route->getPath();
    //     //     }
    //     // }
    //     $response = new Response();
    //     $response->setContent(json_encode($data));
    //     $response->headers->set('Content-Type', 'application/json');

    //     // $response = new JsonResponse($data);
    //     // $response->setEncodingOptions(
    //     //     $response->getEncodingOptions() | JSON_PRETTY_PRINT
    //     // );
    //     return $response;
    // }


    // #[Route('/api/quote', name: "api.quote")]
    // public function api_quote(): Response
    // {
    //     return $this->render('api.quote.html.twig');
    // }

    // #[Route("/lucky/number")]
    // public function jsonNumber(): Response
    // {
    //     $number = random_int(0, 100);

    //     $data = [
    //         'lucky-number' => $number,
    //         'lucky-message' => 'Hi there!',
    //     ];

    //     // $response = new Response();
    //     // $response->setContent(json_encode($data));
    //     // $response->headers->set('Content-Type', 'application/json');

    //     // return $response;

    //     //return new JsonResponse($data);

    //     $response = new JsonResponse($data);
    //     $response->setEncodingOptions(
    //         $response->getEncodingOptions() | JSON_PRETTY_PRINT
    //     );
    //     return $response;
    // }
}
