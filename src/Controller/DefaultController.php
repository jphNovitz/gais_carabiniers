<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\InfoWindow;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ClubRepository $clubRepository): Response
    {

        return $this->render('landing/index.html.twig', [
        ]);
    }
}
