<?php

namespace App\Service;

use App\Contract\MeetingParticipantPositionerInterface;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Enum\MeetingStatus;
use App\Repository\MeetingParticipantRepository;
use App\Repository\MeetingRepository;

readonly class MeetingParticipantPositioner implements MeetingParticipantPositionerInterface
{
    public function __construct(private readonly MeetingRepository $meetingRepository){}
    public function order(Meeting $meeting): void
    {
        $participants = array_values(
            $meeting->getParticipants()
                ->filter(fn(MeetingParticipant $participant) => $participant->getShooter() !== null)
                ->toArray()
        );

        $meeting->getParticipants()->clear();
        $this->meetingRepository->save($meeting, true); // ← DELETE avant INSERT

        foreach ($participants as $i => $sourceParticipant) {
            $participant = new MeetingParticipant();
            $participant->setShooter($sourceParticipant->getShooter());
            $participant->setShootingCategory($sourceParticipant->getShootingCategory());
            $participant->setMeeting($meeting);
            $participant->setPosition($i + 1);
            $meeting->addParticipant($participant);
        }

        if (count($participants) > 0) {
            if ($meeting->getStatus() === MeetingStatus::DRAFT) {
                $meeting->setStatus(MeetingStatus::READY);
            }
        } else {
            if ($meeting->getStatus() === MeetingStatus::READY) {
                $meeting->setStatus(MeetingStatus::DRAFT);
            }
        }
    }
}
