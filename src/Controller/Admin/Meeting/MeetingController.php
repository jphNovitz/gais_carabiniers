<?php

namespace App\Controller\Admin\Meeting;

use App\Contract\MeetingCreatorInterface;
use App\Contract\MeetingParticipantAdderInterface;
use App\Dto\MeetingDto;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Enum\MeetingStatus;
use App\Form\AddParticipantsType;
use App\Form\MeetingParticipantType;
use App\Mapper\MeetingMapper;
use App\Mapper\MeetingParticipantMapper;
use App\Repository\MeetingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/meetings')]
final class MeetingController extends AbstractController
{
    public function __construct(private readonly MeetingRepository $meetingRepository)
    {
    }

    #[Route(name: 'admin_meeting_index', methods: ['GET'])]
    public function index(): Response
    {

        $meetings = $this->meetingRepository->findIndex();
        return $this->render('admin/meeting/index.html.twig', [
            'meetings' => $meetings
        ]);
    }

    #[Route('/create', name: 'admin_meeting_create', methods: ['POST'])]
    public function create(MeetingCreatorInterface $meetingCreator): Response
    {
        $meetingId = $meetingCreator->createMeeting();

        return $this->redirectToRoute('admin_meeting_add_participant',
            ['id' => $meetingId],
            Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/add-participants', name: 'admin_meeting_add_participant', methods: ['GET', 'POST'])]
    public function addParticipants(
        Meeting                          $meeting,
        Request                          $request,
        MeetingParticipantAdderInterface $participantAdder,
        EntityManagerInterface            $em
    ): Response
    {
        $form = $this->createForm(AddParticipantsType::class, null, ['meeting' => $meeting]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $members = $form->get('shooters')->getData();
            $addedCount = $participantAdder->addMany($meeting, $members);

            if ($addedCount > 0) {
                $em->flush();

                $this->addFlash('success', 'meeting.participants.add.success');

                return $this->redirectToRoute(
                    'admin_meeting_show',
                    ['id' => $meeting->getId()],
                    Response::HTTP_SEE_OTHER
                );
            }

            $this->addFlash('warning', 'meeting.no.new.add');
        }

        return $this->render('admin/meeting/add-participants.html.twig', [
            'meeting' => $meeting,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'admin_meeting_show', methods: ['GET'])]
    public function show($id): Response
    {
        $meeting = $this->meetingRepository->findWithParticipants($id);

        if (($meeting->getStatus() === MeetingStatus::DRAFT->value) ||
            ($meeting->getStatus() === MeetingStatus::READY->value)) {
            return $this->render('admin/meeting/preparation.html.twig', [
                'meeting' => $meeting,
            ]);
        }

        // Séance en cours ou terminée
        $standingDto = $this->meetingRepository->findWithScores($id);

        return $this->render('admin/meeting/standing.html.twig', [
            'meeting' => $meeting,
            'standing' => $standingDto
        ]);

    }
}
