<?php

namespace App\Service;

use App\Contract\MeetingParticipantShufflerInterface;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Enum\MeetingStatus;
use App\Enum\ShootingCategory;
use App\Repository\MeetingParticipantRepository;

class MeetingParticipantShuffler implements MeetingParticipantShufflerInterface
{
    public function __construct(
        private readonly MeetingParticipantRepository $mpRepo,
    ) {
    }

    public function addMany(Meeting $meeting, iterable $members): int
    {

        $existingIds = array_flip(
            array_map(fn($p) => $p->getShooter()->getId(), $meeting->getParticipants()->toArray())
        );

        $pos = $meeting->getId()
            ? $this->mpRepo->maxPosition($meeting) + 1
            : 1;
        $added = 0;

        $membersArray = $members instanceof \Traversable
            ? iterator_to_array($members)
            : (array) $members;

        shuffle($membersArray);

        foreach ($membersArray as $member) {
            if (isset($existingIds[$member->getId()])) continue;

            $mp = (new MeetingParticipant())
                ->setMeeting($meeting)
                ->setShooter($member)
                ->setShootingCategory(ShootingCategory::fromUsesSupport($member->isUsesSupport()))
                ->setPosition($pos++);

            $meeting->addParticipant($mp);
            ++$added;
        }

        if (count($meeting->getParticipants()) > 0 && $meeting->getStatus() === MeetingStatus::DRAFT) {
            $meeting->setStatus(MeetingStatus::READY);
        }

        return $added;
    }

    public function syncParticipants(Meeting $meeting, iterable $selectedMembers): void
    {
        $membersArray = $selectedMembers instanceof \Traversable
            ? iterator_to_array($selectedMembers)
            : (array) $selectedMembers;

        // IDs des membres sélectionnés
        $selectedIds = array_map(fn($m) => $m->getId(), $membersArray);

        // Supprime les participants qui ne sont plus sélectionnés
        foreach ($meeting->getParticipants() as $participant) {
            if (!in_array($participant->getShooter()->getId(), $selectedIds)) {
                $meeting->removeParticipant($participant);
            }
        }

        // Ajoute les nouveaux membres (ceux qui ne sont pas encore participants)
        $existingIds = array_map(
            fn($p) => $p->getShooter()->getId(),
            $meeting->getParticipants()->toArray()
        );

        $newMembers = array_filter(
            $membersArray,
            fn($m) => !in_array($m->getId(), $existingIds)
        );

        shuffle($newMembers);

        $pos = $this->mpRepo->maxPosition($meeting) + 1;

        foreach ($newMembers as $member) {
            $mp = (new MeetingParticipant())
                ->setMeeting($meeting)
                ->setShooter($member)
                ->setShootingCategory(ShootingCategory::fromUsesSupport($member->isUsesSupport()))
                ->setPosition($pos++);

            $meeting->addParticipant($mp);
        }

        if (count($meeting->getParticipants()) > 0 && $meeting->getStatus() === MeetingStatus::DRAFT) {
            $meeting->setStatus(MeetingStatus::READY);
        }
    }

}
