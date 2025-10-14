<?php

namespace App\Service;

use App\Contract\RoundManagerInterface;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Entity\Round;
use App\Entity\RoundShot;
use App\Repository\RoundShotRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoundManager implements RoundManagerInterface
{

    public function __construct(private EntityManagerInterface $entityManager,
                                private RoundShotRepository $roundShotRepository)
    {
    }
    public function createNextRound(Meeting $meeting): Round
    {
        $round = new Round();
        $round->setMeeting($meeting);
        $round->setStatus('running');

        $this->entityManager->persist($round);
        $this->entityManager->flush();
        return $round;
    }

    public function recordShot(Round $round, MeetingParticipant $participant, int $score): void
    {
        $roundShot = new RoundShot();
        $roundShot->setRound($round);
        $roundShot->setMeetingParticipant($participant);
        $roundShot->setScore($score);
        $this->roundShotRepository->save($roundShot, true);

    }

    public function closeRound(Round $round): void
    {
        // TODO: Implement closeRound() method.
    }



}