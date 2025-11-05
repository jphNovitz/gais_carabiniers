<?php

namespace App\Service;

use App\Contract\MeetingCloserInterface;
use App\Contract\MeetingCreatorInterface;
use App\Entity\Meeting;
use App\Entity\MeetingSnapshot;
use App\Enum\MeetingStatus;
use App\Repository\MeetingRepository;
use App\Repository\MeetingSnapshotRepository;
use Doctrine\ORM\EntityManagerInterface;

class MeetingCloser implements MeetingCloserInterface
{
    public function __construct(private readonly MeetingRepository $meetingRepository, private readonly MeetingSnapshotRepository $meetingSnapshotRepository)
    {
    }

    public function close(Meeting $meeting): bool
    {
        $standings  = $this->meetingRepository->findSnapshot($meeting->getId());
        foreach ($standings as $index => $standing) {
            $snapshot = new MeetingSnapshot();
            $snapshot->setMeetingPosition($index + 1);
            $snapshot->setMeeting($meeting);
            $participant = $meeting->getParticipants()->filter(function ($participant) use ($standing) {
                return $participant->getId() === $standing['participantId'];
            })->first()->getShooter();
            $snapshot->setParticipant($participant);
            $snapshot->setShooterName($participant->getFullName());
            $snapshot->setTotalScore($standing['totalScore']);
            $snapshot->setComputedAt(new \DateTimeImmutable());
            $snapshot->setYear((int)$meeting->getDate()->format('Y'));
            $snapshot->setMeetingLabel($meeting->getLabel());


            $this->meetingSnapshotRepository->save($snapshot, true);
        }

        $meeting->setStatus(MeetingStatus::CLOSED);
        $meeting->setClosedAt(new \DateTimeImmutable());
        $this->meetingRepository->save($meeting, true);
        return  true;
        dd($standings);

        $meeting->setStatus(MeetingStatus::CLOSED);
        $meeting->setClosedAt(new \DateTimeImmutable());


        $this->meetingRepository->save($meeting, true);

        return true;
    }
}
