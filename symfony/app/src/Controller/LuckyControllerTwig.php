<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route('/', name: "me")]
    #[CustomAnnotation("Give presentation about my selg with a picture.")]
    public function me(): Response
    {
        return $this->render('me.html.twig');
    }

    #[Route('/about', name: "about")]
    #[CustomAnnotation("Tell about the cours and its perpouse.")]
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
        //      $response->getEncodingOptions() | JSON_PRETTY_PRINT
        // );
        return $this->render('lucky.html.twig', $data);
    }
}
