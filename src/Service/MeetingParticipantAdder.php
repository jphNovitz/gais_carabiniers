<?php

namespace App\Service;

use App\Contract\MeetingParticipantAdderInterface;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Entity\Member;
use App\Enum\MeetingStatus;
use App\Repository\MeetingParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;

class MeetingParticipantAdder implements MeetingParticipantAdderInterface
{
    public function __construct(
        private readonly MeetingParticipantRepository $mpRepo,
    ) {
    }

    public function addMany(Meeting $meeting, iterable $members): int
    {
        // dédup en 1 fois
        $existingIds = array_flip(
            array_map(fn($p) => $p->getShooter()->getId(), $meeting->getParticipants()->toArray())
        );

        $pos   = $this->mpRepo->maxPosition($meeting) + 1;
        $added = 0;

        foreach ($members as $member) {
            if (isset($existingIds[$member->getId()])) continue;

            $mp = (new MeetingParticipant())
                ->setMeeting($meeting)
                ->setShooter($member)
                ->setPosition($pos++);

            $meeting->addParticipant($mp);
            ++$added;
        }

        if (count($meeting->getParticipants()) > 0 && $meeting->getStatus() === MeetingStatus::DRAFT) {
            $meeting->setStatus(MeetingStatus::READY);
        }

        return $added;
    }

}
