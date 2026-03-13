<?php

namespace App\Service;

use App\Contract\RoundManagerInterface;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Entity\Round;
use App\Entity\RoundShot;
use App\Enum\MeetingStatus;
use App\Enum\RoundStatus;
use App\Repository\RoundShotRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoundManager implements RoundManagerInterface
{

    public function __construct(private EntityManagerInterface       $entityManager,
                                private readonly RoundShotRepository $roundShotRepository)
    {
    }
    public function createNextRound(Meeting $meeting): Round
    {
        $existingRound = $meeting->getRounds()->filter(
            fn($r) => !$r->isClosed()
        )->last();

        if ($existingRound) {
            return $existingRound;
        }

        $round = new Round();
        $round->setMeeting($meeting);
        $round->setNumber($meeting->getRounds()->count() + 1);
        $round->setStatus(RoundStatus::IN_PROGRESS);
        $meeting->setStatus(MeetingStatus::IN_PROGRESS);

        $participants = $meeting->getParticipants()->toArray();
        usort($participants, fn($a, $b) => $a->getPosition() <=> $b->getPosition());

        foreach ($participants as $participant) {
            $roundShot = new RoundShot();
            $roundShot->setRound($round);
            $roundShot->setMeetingParticipant($participant);
            $round->addRoundShot($roundShot);
            $this->entityManager->persist($roundShot);
        }

        $this->entityManager->persist($round);
        $this->entityManager->flush();
        return $round;
    }

    public function recordShot(Round $round, MeetingParticipant $participant, int $score): void
    {
        $roundShot = new RoundShot();
        $roundShot->setRound($round);
        $roundShot->setMeetingParticipant($participant);
        $this->roundShotRepository->save($roundShot, true);

    }

    public function closeRound(Round $round): void
    {
        $round->setStatus(RoundStatus::COMPLETED);
        $round->setEndedAt(new \DateTimeImmutable());
        $this->entityManager->persist($round);
        $this->entityManager->flush();

    }



}