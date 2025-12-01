<?php

namespace App\Controller\Admin\Meeting;

use App\Contract\MeetingCreatorInterface;
use App\Contract\MeetingParticipantAdderInterface;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Enum\MeetingStatus;
use App\Enum\MeetingType;
use App\Form\AddParticipantsType;
use App\Repository\MeetingRepository;
use App\Service\MeetingCloser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

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
    public function create(MeetingCreatorInterface $meetingCreator, Request $request): Response
    {
        $token = $request->request->get('_token');

        if (!$this->isCsrfTokenValid('meeting_create', $token)) {
            throw new InvalidCsrfTokenException();
        }

        $typeValue = $request->request->get('meeting_type', 'COMPETITION');

        try {
            $meetingType = MeetingType::from($typeValue);
        } catch (\Error $e) {
            $this->addFlash('error', 'Type de séance invalide');
            return $this->redirectToRoute('admin_meeting_index', [], Response::HTTP_SEE_OTHER);
        }

        $meetingId = $meetingCreator->createMeeting($meetingType);

        return $this->redirectToRoute(
            'admin_meeting_add_participant',
            ['id' => $meetingId],
            Response::HTTP_SEE_OTHER
        );
    }

    #[Route('/{id}/add-participants', name: 'admin_meeting_add_participant', methods: ['GET', 'POST'])]
    public function addParticipants(
        Meeting $meeting,
        Request $request,
        MeetingParticipantAdderInterface $participantAdder,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(AddParticipantsType::class, null, ['meeting' => $meeting]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $members = $form->get('shooters')->getData();
            $addedCount = $participantAdder->addMany($meeting, $members);

            if ($addedCount > 0) {
                $em->flush();
                $this->addFlash('success', 'meeting.participants.add.success');

                return $this->redirectToRoute('admin_meeting_show', ['id' => $meeting->getId()], Response::HTTP_SEE_OTHER);
            }

            $this->addFlash('warning', 'meeting.no.new.add');
        }

        return $this->render('admin/meeting/add-participants.html.twig', [
            'meeting' => $meeting,
            'form' => $form,
        ]);
    }

    #[Route('/meeting/{id}/participant/{participant}', name: 'admin_meeting_participant_update_position', methods: ['POST'])]
    public function reorder(Meeting $meeting, MeetingParticipant $participant, Request $request, EntityManagerInterface $em): Response
    {
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('update_position_' . $participant->getId(), $token)) {
            $this->addFlash('error', 'Token CSRF invalide');
            return $this->redirectToRoute('admin_meeting_show', ['id' => $meeting->getId()]);
        }

        $newPosition = (int)$request->request->get('position');

        if ($newPosition < 1) {
            $this->addFlash('error', 'Position invalide');
            return $this->redirectToRoute('admin_meeting_show', ['id' => $meeting->getId()]);
        }

        if ($participant->getPosition() === $newPosition) {
            $this->addFlash('info', 'meeting.participant.reorder.nochange');
            return $this->redirectToRoute('admin_meeting_show', ['id' => $meeting->getId()]);
        }

        $oldPosition = $participant->getPosition();


        $currentOccupant = $em->getRepository(MeetingParticipant::class)
            ->findOneBy([
                'meeting' => $meeting,
                'position' => $newPosition
            ]);

        if ($currentOccupant) {
            $currentOccupant->setPosition(-999); // Position temporaire
            $em->flush();
        }

        $participant->setPosition($newPosition);
        $em->flush();

        if ($currentOccupant) {
            $currentOccupant->setPosition($oldPosition);
            $em->flush();
        }

        $this->addFlash('success', 'meeting.participant.reorder.success');

        return $this->redirectToRoute('admin_meeting_show', ['id' => $meeting->getId()]);
    }


    #[Route('/{id}', name: 'admin_meeting_show', methods: ['GET'])]
    public function show($id): Response
    {
        $meeting = $this->meetingRepository->findWithParticipants($id);
        if (
            ($meeting->getStatus() === MeetingStatus::DRAFT) ||
                 ($meeting->getStatus() === MeetingStatus::READY)
        ) {
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

    #[Route('/{id}/close', name: 'admin_meeting_close', methods: ['POST'])]
    public function close(Meeting $meeting, MeetingCloser $meetingCloser): Response
    {

        $meetingCloser->close($meeting);


        $this->addFlash('success', 'meeting.close.success');

        return $this->redirectToRoute('admin_meeting_show', [
            'id' => $meeting->getId()
        ]);
    }
}
