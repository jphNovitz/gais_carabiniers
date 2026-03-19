<?php

namespace App\Controller;

use App\Repository\ClubRepository;
use App\Repository\FacebookEventRepository;
use App\Repository\MeetingSnapshotRepository;
use App\Repository\PostRepository;
use App\Repository\YearSnapshotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[Cache(maxage: 31536000, public: true, mustRevalidate: true)]
    public function index(ClubRepository $clubRepository,
                          FacebookEventRepository $facebookEventRepository,
                          PostRepository $postRepository,
                          YearSnapshotRepository $yearSnapshotRepository): Response
    {
        $edito = $postRepository->findHomeEdito('Le mot du comité');
        $facebookEvents = $facebookEventRepository->findNextFutureElement();
        $snapshot = $yearSnapshotRepository->findSeasonStandings(date('Y'));


        return $this->render('landing/index.html.twig', [
            'edito' => $edito,
            'facebookEvent' => $facebookEvents ?? null,
            'snapshot' => $snapshot,
        ]);
    }
}
