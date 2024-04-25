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
        return $this->render('myreport/me.html.twig');
    }

    #[Route('/about', name: "about")]
    public function about(): Response
    {
        return $this->render('myreport/about.html.twig');
    }

    #[Route('/report', name: "report")]
    public function report(): Response
    {
        return $this->render('myreport/report.html.twig');
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
        return $this->render('myreport/lucky.html.twig', $data);
    }
}
