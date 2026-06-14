<?php

namespace App\Controller\Meeting;

use App\Entity\Meeting;
use App\Repository\MeetingSnapshotRepository;
use App\Service\MeetingOrganizer;
use App\Contract\CategorizedStandingBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MeetingController extends AbstractController
{

    public function __construct(
        private readonly MeetingSnapshotRepository $meetingSnapshotRepository,
        private readonly CategorizedStandingBuilderInterface $categorizedStandingBuilder,
    ) {}

    #[Route('/resultats-tir-aux-plaquettes', name: 'app_meeting_index', methods: ['GET'])]
    public function index(MeetingOrganizer $organizer): Response
    {
        $groupedMeetings = $organizer->getMeetingsGroupedByYear();

        return $this->render('meeting/index.html.twig', [
            'groupedMeetings' => $groupedMeetings
        ]);
    }

    #[Route('/resultats-tir-aux-plaquettes/{slug}', name: 'app_meeting_show', methods: ['GET'])]
    public function show(Meeting $meeting): Response
    {
        $standing = $this->meetingSnapshotRepository->findByMeetingId($meeting->getId());

        return $this->render('meeting/show.html.twig', [
            'meeting' => $meeting,
            'standing' => $standing,
            'categorizedStanding' => $this->categorizedStandingBuilder->categorizeMeetingStanding($standing),
        ]);
    }

    #[Route('/classement', name: 'meeting_standing', methods: ['GET'])]
    public function standing(): Response
    {
        return $this->render('meeting/standing.html.twig', [
        ]);
    }

}
