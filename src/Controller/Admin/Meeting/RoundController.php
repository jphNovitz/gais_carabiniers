<?php

namespace App\Controller\Admin\Meeting;


use App\Contract\RoundManagerInterface;
use App\Entity\Meeting;
use App\Entity\Round;
use App\Entity\RoundShot;
use App\Enum\RoundStatus;
use App\Form\RoundShotType;
use App\Form\RoundType;
use App\Mapper\MeetingMapper;
use App\Repository\MeetingParticipantRepository;
use App\Repository\RoundRepository;
use App\Repository\RoundShotRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/')]
final class RoundController extends AbstractController
{
    public function __construct(
        private RoundRepository $roundRepository,
        private RoundShotRepository $roundShotRepository,
        private RoundManagerInterface $roundManager
    ) {}

    #[Route('meetings/{id}/round', name: 'admin_round_index')]
    public function index(Meeting $meeting): Response
    {
        if ($meeting->getRounds()->count() > 0) {
            die('vide');
        } else {
            return $this->render('admin/meeting/preparation.html.twig', [
                'meeting' => $meeting,
            ]);
        }
    }

//    #[Route('meetings/{id}/rounds/start', name: 'round_start', methods: ['GET', 'POST'])]
//    public function start(Meeting $meeting): Response
//    {
//        $round = $this->roundRepository->findCurrentRound($meeting)
//            ?? $this->roundManager->createNextRound($meeting);
//
//        return $this->redirectToRoute('round_next', [
//            'meeting' => $meeting->getId(),
//            'round' => $round->getId(),
//            'position' => 1
//        ]);
//    }

//    #[Route('meetings/{meeting}/rounds/start', name: 'admin_round_start', methods: ['GET', 'POST'])]
//    public function start(Meeting $meeting, EntityManagerInterface $em, AdminContext $context): Response
//    {
//        $meeting = $context->getEntity()->getInstance();
//
//        $round = $this->roundManager->createNextRound($meeting);
//
//        $this->addFlash('success', 'Round démarré avec succès');
//
//        return $this->redirectToRoute('admin_round_all_shots', [
//            'meeting' => $meeting->getId(),
//            'round' => $round->getId()
//        ]);
//    }
    #[Route('meetings/{meeting}/rounds/all-shots', name: 'admin_round_all_shots', methods: ['GET', 'POST'])]
    public function allShots(
        Meeting $meeting,
//        Round $round,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $round = $this->roundManager->createNextRound($meeting);

        // Créer les RoundShots s'ils n'existent pas encore
        if ($round->getRoundShots()->isEmpty()) {
            foreach ($meeting->getParticipants() as $participant) {
                $roundShot = new RoundShot();
                $roundShot->setMeetingParticipant($participant);
                $roundShot->setRound($round);
                $round->addRoundShot($roundShot);
            }
        }

        $form = $this->createForm(RoundType::class, $round);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->roundManager->closeRound($round);

            $this->addFlash('success', 'Scores enregistrés avec succès');

            return $this->redirectToRoute('admin_meeting_detail', [
                'entityId' => $meeting->getId()
            ]);
        }

        return $this->render('admin/meeting/round/all_shots.html.twig', [
            'meeting' => $meeting,
            'round' => $round,
            'form' => $form,
        ]);
    }

    // round by round, participant by participant
    #[Route('meetings/{meeting}/rounds/{round}/next/{position}', name: 'round_next', methods: ['GET', 'POST'])]
    public function next(Meeting $meeting, Round $round, int $position, MeetingParticipantRepository $participantRepository,  Request $request): Response
    {
        $participant = $participantRepository->findOneBy([
            'meeting' => $meeting,
            'position' => $position
        ]);


        $roundShot = new RoundShot();
        $roundShot->setMeetingParticipant($participant);
        $roundShot->setRound($round);

        $form = $this->createForm(RoundShotType::class, $roundShot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->roundManager->recordShot($round, $participant, $roundShot->getScore());

            if ($position >= $meeting->getParticipants()->count()) {
                $this->roundManager->closeRound($round);
                return $this->redirectToRoute('admin_meeting_show', ['id' => $meeting->getId()]);
            } else {
                return $this->redirectToRoute('round_next', [
                    'meeting' => $meeting->getId(),
                    'round' => $round->getId(),
                    'position' => $position + 1
                ]);
            }
        }
        return $this->render('admin/meeting/round/next.html.twig', [
            'meeting' => $meeting,
            'round' => $round,
            'participant' => $participant,
            'form' => $form->createView(),
        ]);
    }

//    #[Route('meetings/{id}', name: 'meeting_show', methods: ['GET'])]
//    public function show(Meeting $meeting): Response
//    {
//        $dto = MeetingMapper::fromEntity($meeting);
//
//        // Reuse the existing admin template for now to keep changes minimal
//        return $this->render('admin/meeting/show.html.twig', [
//            'meeting' => $dto,
//        ]);
//    }

}

