<?php

namespace App\Controller\Facebook;

use App\Entity\FacebookEvent;
use App\Mapper\FacebookEventMapper;
use App\Repository\FacebookEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FacebookEventController extends AbstractController
{
    #[Route('/competitions-tir-sportif/agenda', name: 'app_agenda')]
    public function agenda(FacebookEventRepository $facebookEventRepository): Response
    {
        return $this->render('facebook/index.html.twig', [
            'facebook_events' => $facebookEventRepository->findAllFutureElements(),
            'facebookEventsPast' => $facebookEventRepository->findAllPastElements()
        ]);
    }
    #[Route('/competitions-tir-sportif/agenda/{slug}', name: 'app_agenda_show')]
    public function show(FacebookEvent $facebookEvent, FacebookEventMapper $facebookEventMapper): Response
    {
        $facebookEventDto = $facebookEventMapper->fromEntity($facebookEvent);
        return $this->render('facebook/show.html.twig', [
            'facebookEvent' => $facebookEventDto
        ]);
    }
}
