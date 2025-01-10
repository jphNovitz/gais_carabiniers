<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use App\Repository\FacebookEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    #[Cache(maxage: 31536000, public: true, mustRevalidate: true)]
    public function index(ClubRepository $clubRepository, FacebookEventRepository $facebookEventRepository): Response
    {
        $facebookEvents = $facebookEventRepository->findLastFutureElements(3);
        // dd($facebookEvents);
        return $this->render('landing/index.html.twig', [
            'facebookEvents' => $facebookEvents,
        ]);
    }
}
