<?php

namespace App\Controller\Facebook;

use App\Repository\FacebookEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FacebookEventController extends AbstractController
{
    #[Route('/agenda', name: 'app_agenda')]
    public function agenda(FacebookEventRepository $facebookEventRepository): Response
    {
        return $this->render('facebook/index.html.twig', [
            'facebook_events' => $facebookEventRepository->findAllFutureElements(),
            'facebookEventsPast' => $facebookEventRepository->findAllPastElements()
        ]);
    }
}
