<?php

namespace App\Controller\Admin\Meeting;

use App\Contract\MeetingCreatorInterface;
use App\Contract\MeetingParticipantAdderInterface;
use App\Dto\MeetingDto;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Form\AddParticipantsType;
use App\Form\MeetingParticipantType;
use App\Mapper\MeetingMapper;
use App\Mapper\MeetingParticipantMapper;
use App\Repository\MeetingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/meetings')]
final class MeetingController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route(name: 'admin_meeting_index', methods: ['GET'])]
    public function index(MeetingRepository $meetingRepository): Response
    {

        $meetings = $meetingRepository->findIndex();
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

    #[route('/{id}/add-participants', name: 'admin_meeting_add_participant', methods: ['GET', 'POST'])]
    public function update(Meeting $meeting, Request $request, MeetingParticipantAdderInterface $participantAdder): Response
    {
        $form = $this->createForm(AddParticipantsType::class, null, ['meeting' => $meeting]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $members = $form->get('shooters')->getData();
            if ($participantAdder->addMany($meeting, $members) > 0) {
                $this->addFlash('success', 'Participants added successfully.');
                return $this->redirectToRoute('admin_meeting_show', ['id' => $meeting->getId()], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('warning', 'No new participants were added.');
                return $this->redirectToRoute('admin_meeting_add_participant', ['id' => $meeting->getId()], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->render('admin/meeting/participant.html.twig', [
            'meeting' => $meeting,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_meeting_show', methods: ['GET'])]
    public function show(Meeting $meeting): Response
    {
        $dto = MeetingMapper::fromEntity($meeting);
        return $this->render('admin/meeting/show.html.twig', [
            'meeting' => $dto,
        ]);
    }
}
