<?php

namespace App\Service;

use App\Contract\MeetingCloserInterface;
use App\Contract\MeetingCreatorInterface;
use App\Entity\Meeting;
use App\Entity\MeetingSnapshot;
use App\Enum\MeetingStatus;
use App\Enum\MeetingType;
use App\Repository\MeetingRepository;
use App\Repository\MeetingSnapshotRepository;
use Doctrine\ORM\EntityManagerInterface;

class MeetingCloser implements MeetingCloserInterface
{
    public function __construct(private readonly MeetingRepository         $meetingRepository,
                                private readonly MeetingSnapshotRepository $meetingSnapshotRepository)
    {
    }

    public function close(Meeting $meeting): bool
    {
        if ($meeting->getType() === MeetingType::COMPETITION) {
            $standings = $this->meetingRepository->findSnapshot($meeting->getId());

            $lastScore = null;
            $position = 0;
            foreach ($standings as $standing) {
                $score = (int) $standing['totalScore'];

                if ($lastScore === null || $score !== $lastScore) {
                    $position++;
                    $lastScore = $score;
                }


                $snapshot = new MeetingSnapshot();
                $snapshot->setMeetingPosition($position);
                $snapshot->setMeeting($meeting);
                $participant = $meeting->getParticipants()->filter(function ($participant) use ($standing) {
                    return $participant->getId() === $standing['participantId'];
                })->first()->getShooter();
                $snapshot->setParticipant($participant);
                $snapshot->setShooterName($participant);
                $snapshot->setTotalScore($standing['totalScore']);
                $snapshot->setComputedAt(new \DateTimeImmutable());
                $snapshot->setYear((int)$meeting->getDate()->format('Y'));
                $snapshot->setMeetingLabel($meeting->getLabel());


                $this->meetingSnapshotRepository->save($snapshot, true);
            }
        }

        $meeting->setStatus(MeetingStatus::CLOSED);
        $meeting->setClosedAt(new \DateTimeImmutable());
        $this->meetingRepository->save($meeting, true);
        return true;
    }
}
