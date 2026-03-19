<?php

namespace App\Service;

use App\Contract\MeetingParticipantAdderInterface;
use App\Contract\MeetingParticipantPositionerInterface;
use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Entity\Member;
use App\Enum\MeetingStatus;
use App\Enum\MeetingType;
use App\Repository\MeetingParticipantRepository;
use App\Repository\MeetingRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class MeetingParticipantPositioner implements MeetingParticipantPositionerInterface
{
    public function __construct(private readonly MeetingRepository $meetingRepository){}
    public function order(Meeting $meeting): void
    {
        $shooters = $meeting->getParticipants()
            ->map(fn($p) => $p->getShooter())
            ->filter(fn($s) => $s !== null)
            ->toArray();

        $meeting->getParticipants()->clear();
        $this->meetingRepository->save($meeting, true); // ← DELETE avant INSERT

        foreach ($shooters as $i => $shooter) {
            $participant = new MeetingParticipant();
            $participant->setShooter($shooter);
            $participant->setMeeting($meeting);
            $participant->setPosition($i + 1);
            $meeting->addParticipant($participant);
        }

        if (count($shooters) > 0) {
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
