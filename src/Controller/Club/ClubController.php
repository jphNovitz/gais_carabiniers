<?php

namespace App\Controller\Club;

use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\InfoWindow;

class ClubController extends AbstractController
{
    #[Route('/notre-histoire', name: 'app_about')]
    public function index(ClubRepository $clubRepository): Response
    {

        return $this->render('club/about.html.twig', [
//            $clubs = $clubRepository->findAll()[0],
        ]);
    }
}
