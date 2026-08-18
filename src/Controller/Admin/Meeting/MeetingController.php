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

//    #[Route('/meeting/{id}/participant/{participant}', name: 'admin_meeting_participant_update_position', methods: ['POST'])]
//    public function reorder(Meeting $meeting, MeetingParticipant $participant, Request $request, EntityManagerInterface $em): Response
//    {
//        $token = $request->request->get('_token');
//        if (!$this->isCsrfTokenValid('update_position_' . $participant->getId(), $token)) {
//            $this->addFlash('error', 'Token CSRF invalide');
//            return $this->redirectToRoute('adminold_meeting_show', ['id' => $meeting->getId()]);
//        }
//
//        $newPosition = (int)$request->request->get('position');
//
//        if ($newPosition < 1) {
//            $this->addFlash('error', 'Position invalide');
//            return $this->redirectToRoute('adminold_meeting_show', ['id' => $meeting->getId()]);
//        }
//
//        if ($participant->getPosition() === $newPosition) {
//            $this->addFlash('info', 'meeting.participant.reorder.nochange');
//            return $this->redirectToRoute('adminold_meeting_show', ['id' => $meeting->getId()]);
//        }
//
//        $oldPosition = $participant->getPosition();
//
//
//        $currentOccupant = $em->getRepository(MeetingParticipant::class)
//            ->findOneBy([
//                'meeting' => $meeting,
//                'position' => $newPosition
//            ]);
//
//        if ($currentOccupant) {
//            $currentOccupant->setPosition(-999); // Position temporaire
//            $em->flush();
//        }
//
//        $participant->setPosition($newPosition);
//        $em->flush();
//
//        if ($currentOccupant) {
//            $currentOccupant->setPosition($oldPosition);
//            $em->flush();
//        }
//
//        $this->addFlash('success', 'meeting.participant.reorder.success');
//
//        return $this->redirectToRoute('adminold_meeting_show', ['id' => $meeting->getId()]);
//    }


    #[Route('/{id}', name: 'admin_meeting_reorder', methods: ['GET'])]
    public function show($id): Response
    {
        $meeting = $this->meetingRepository->findWithParticipants($id);

            return $this->render('admin/meeting/reorder-particiapants.html.twig', [
                'meeting' => $meeting,
            ]);

    }
    #[Route('/{id}/close', name: 'admin_meeting_close', methods: ['GET'])]
    public function close(Meeting $meeting, MeetingCloser $meetingCloser): Response
    {
        $meetingCloser->close($meeting);

        $this->addFlash('success', 'meeting.close_success');

        return $this->redirectToRoute('admin_meeting_index', [
            'id' => $meeting->getId()
        ]);
    }
}
