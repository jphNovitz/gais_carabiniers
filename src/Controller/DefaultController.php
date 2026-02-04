<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use App\Repository\FacebookEventRepository;
use App\Repository\MeetingSnapshotRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    #[Cache(maxage: 31536000, public: true, mustRevalidate: true)]
    public function index(ClubRepository $clubRepository,
                          FacebookEventRepository $facebookEventRepository,
                          PostRepository $postRepository,
                          MeetingSnapshotRepository $meetingSnapshotRepository): Response
    {
        $edito = $postRepository->findHomeEdito('Edito');
        $facebookEvents = $facebookEventRepository->findLastFutureElements(1);
        $snapshot = $meetingSnapshotRepository->findSeasonStandings(date('Y'));


        return $this->render('landing/index.html.twig', [
            'edito' => $edito,
            'facebookEvent' => $facebookEvents[0] ?? null,
            'snapshot' => $snapshot,
        ]);
    }
}
