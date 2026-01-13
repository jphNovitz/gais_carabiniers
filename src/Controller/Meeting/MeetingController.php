<?php

namespace App\Controller\Meeting;

use App\Dto\MeetingDto;
use App\Entity\Meeting;
use App\Mapper\MeetingMapper;
use App\Repository\MeetingRepository;
use App\Repository\MeetingSnapshotRepository;
use App\Service\MeetingOrganizer;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeetingController extends AbstractController
{

    public function __construct(private readonly MeetingSnapshotRepository $meetingSnapshotRepository)
    {
    }

    #[Route('/competitions-de-tir/', name: 'meeting_index', methods: ['GET'])]
    public function index(MeetingOrganizer $organizer): Response
    {
        $groupedMeetings = $organizer->getMeetingsGroupedByYear();

        return $this->render('meeting/index.html.twig', [
            'groupedMeetings' => $groupedMeetings
        ]);
    }

    #[Route('/competition-de-tir/{id}', name: 'meeting_show', methods: ['GET'])]
    public function show(Meeting $meeting): Response
    {
        $standing = $this->meetingSnapshotRepository->findByMeetingId($meeting->getId());
        return $this->render('meeting/show.html.twig', [
            'meeting' => $meeting,
            'standing' => $standing,
        ]);
    }

    #[Route('/classement', name: 'meeting_standing', methods: ['GET'])]
    public function standing(): Response
    {
        return $this->render('meeting/standing.html.twig', [
        ]);
    }

}
