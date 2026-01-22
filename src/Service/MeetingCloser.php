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
            $year = (int)$meeting->getDate()->format('Y');

            $lastScore = null;
            $position = 0;

            foreach ($standings as $standing) {
                $score = (int) $standing['totalScore'];

                if ($lastScore === null || $score !== $lastScore) {
                    $position++;
                    $lastScore = $score;
                }

                $participant = $meeting->getParticipants()->filter(function ($participant) use ($standing) {
                    return $participant->getId() === $standing['participantId'];
                })->first()->getShooter();

                // Chercher le snapshot existant pour ce participant cette année
                $snapshot = $this->meetingSnapshotRepository->findOneBy([
                    'participant' => $participant,
                    'year' => $year
                ]);

                if ($snapshot) {
                    // Sauvegarder l'ancienne position avant mise à jour
                    $snapshot->setMeetingPrevPosition($snapshot->getMeetingPosition());

                    // Mise à jour du snapshot existant (cumul)
                    $snapshot->setTotalScore($snapshot->getTotalScore() + $standing['totalScore']);
                    $snapshot->setMeetingCount($snapshot->getMeetingCount() + 1);

                    // Calculer la moyenne
                    $snapshot->setAverageHits($snapshot->getTotalScore() / $snapshot->getMeetingCount());

                    $snapshot->setComputedAt(new \DateTimeImmutable());
                    $snapshot->setMeetingLabel($meeting->getLabel());
                } else {
                    // Premier meeting de l'année pour ce participant
                    $snapshot = new MeetingSnapshot();
                    $snapshot->setParticipant($participant);
                    $snapshot->setShooterName($participant);
                    $snapshot->setYear($year);
                    $snapshot->setTotalScore($standing['totalScore']);
                    $snapshot->setMeetingCount(1);

                    // Moyenne = score total (premier meeting)
                    $snapshot->setAverageHits($standing['totalScore']);

                    // Pas de position précédente pour le premier meeting
                    $snapshot->setMeetingPrevPosition(null);

                    $snapshot->setComputedAt(new \DateTimeImmutable());
                    $snapshot->setMeetingLabel($meeting->getLabel());

                    // ⚠️ IMPORTANT : Définir clubName si vous l'utilisez dans la requête
                    // $snapshot->setClubName($standing['clubName'] ?? null);
                }

                // La position est recalculée après chaque meeting dans le classement général
                $snapshot->setMeetingPosition($position);
                $snapshot->setMeeting($meeting); // Dernier meeting traité

                $this->meetingSnapshotRepository->save($snapshot, true);
            }
        }

        $meeting->setStatus(MeetingStatus::CLOSED);
        $meeting->setClosedAt(new \DateTimeImmutable());
        $this->meetingRepository->save($meeting, true);

        return true;
    }
}
