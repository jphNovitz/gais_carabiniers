<?php

namespace App\Controller\Meeting;

use App\Contract\CategorizedStandingBuilderInterface;
use App\Entity\Meeting;
use App\Repository\MeetingSnapshotRepository;
use App\Service\MeetingOrganizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function show(Meeting $meeting): RedirectResponse
    {
        return $this->redirectToRoute('app_meeting_show_category', [
            'slug' => $meeting->getSlug(),
            'category' => 'standard',
        ]);
    }

    #[Route(
        '/resultats-tir-aux-plaquettes/{slug}/{category}',
        name: 'app_meeting_show_category',
        requirements: ['category' => 'standard|appuye|classique'],
        methods: ['GET']
    )]
    public function showCategory(Meeting $meeting, string $category): Response
    {
        if ($category === 'classique') {
            return $this->redirectToRoute('app_meeting_show_category', [
                'slug' => $meeting->getSlug(),
                'category' => 'standard',
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        $standing = $this->meetingSnapshotRepository->findByMeetingId($meeting->getId());
        $categorizedStanding = $this->categorizedStandingBuilder->categorizeMeetingStanding($standing);
        $categoryConfig = $this->categoryConfig($category);

        return $this->render('meeting/show.html.twig', [
            'meeting' => $meeting,
            'standingRows' => $categorizedStanding[$categoryConfig['key']],
            'currentCategory' => $category,
            'currentCategoryTitle' => $categoryConfig['title'],
            'emptyCategoryMessage' => $categoryConfig['emptyMessage'],
        ]);
    }

    #[Route('/classement', name: 'meeting_standing', methods: ['GET'])]
    public function standing(): RedirectResponse
    {
        return $this->redirectToRoute('meeting_standing_category', [
            'category' => 'standard',
        ]);
    }

    #[Route('/classement/{category}', name: 'meeting_standing_category', requirements: ['category' => 'standard|appuye|classique'], methods: ['GET'])]
    public function standingCategory(string $category): Response
    {
        if ($category === 'classique') {
            return $this->redirectToRoute('meeting_standing_category', [
                'category' => 'standard',
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        return $this->render('meeting/standing.html.twig', [
            'category' => $category,
            'standingTitle' => $category === 'appuye' ? 'Classement annuel appuyé' : 'Classement annuel standard',
        ]);
    }

    /**
     * @return array{key: string, title: string, emptyMessage: string}
     */
    private function categoryConfig(string $category): array
    {
        return match ($category) {
            'appuye' => [
                'key' => 'supported',
                'title' => 'Tir plaquettes appuyé',
                'emptyMessage' => 'Aucun résultat appuyé disponible pour ce tir.',
            ],
            default => [
                'key' => 'standard',
                'title' => 'Tir plaquettes standard',
                'emptyMessage' => 'Aucun résultat standard disponible pour ce tir.',
            ],
        };
    }

}
