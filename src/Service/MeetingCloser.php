<?php

namespace App\Service;

use App\Contract\MeetingCloserInterface;
use App\Entity\Meeting;
use App\Entity\MeetingSnapshot;
use App\Entity\YearSnapshot;
use App\Enum\MeetingStatus;
use App\Enum\MeetingType;
use App\Enum\ShootingCategory;
use App\Repository\MeetingRepository;
use App\Repository\MeetingSnapshotRepository;
use App\Repository\YearSnapshotRepository;
use Doctrine\ORM\EntityManagerInterface;

class MeetingCloser implements MeetingCloserInterface
{
    public function __construct(
        private readonly MeetingRepository         $meetingRepository,
        private readonly MeetingSnapshotRepository $meetingSnapshotRepository,
        private readonly YearSnapshotRepository   $yearSnapshotRepository,
        private readonly EntityManagerInterface    $em
    ) {}

    public function close(Meeting $meeting): bool
    {
        $standings = $this->meetingRepository->findSnapshot($meeting->getId());
        $year = (int) $meeting->getDate()->format('Y');

        // ----------------------------------------------------------------
        // ÉTAPE 1 : Créer/mettre à jour les MeetingSnapshot (tous types)
        // ----------------------------------------------------------------
        foreach ($standings as $standing) {
            $participant = $meeting->getParticipants()->filter(function ($p) use ($standing) {
                return $p->getId() === $standing['participantId'];
            })->first()->getShooter();

            $meetingSnapshot = $this->meetingSnapshotRepository->findOneBy([
                'meeting'     => $meeting,
                'participant' => $participant,
            ]);

            if (!$meetingSnapshot) {
                $meetingSnapshot = new MeetingSnapshot();
                $meetingSnapshot->setMeeting($meeting);
                $meetingSnapshot->setParticipant($participant);
            }

            $shootingCategory = $standing['shootingCategory'] instanceof ShootingCategory
                ? $standing['shootingCategory']
                : ShootingCategory::from($standing['shootingCategory']);

            $meetingSnapshot->setScore((int) $standing['totalScore']);
            $meetingSnapshot->setShootingCategory($shootingCategory);
            $meetingSnapshot->setComputedAt(new \DateTimeImmutable());

            $this->em->persist($meetingSnapshot);
        }

        // ----------------------------------------------------------------
        // ÉTAPE 2 : YearSnapshot (COMPETITION uniquement)
        // ----------------------------------------------------------------
        if ($meeting->getType() === MeetingType::COMPETITION) {
            foreach ($standings as $standing) {
                $participant = $meeting->getParticipants()->filter(function ($p) use ($standing) {
                    return $p->getId() === $standing['participantId'];
                })->first()->getShooter();
                $shootingCategory = $standing['shootingCategory'] instanceof ShootingCategory
                    ? $standing['shootingCategory']
                    : ShootingCategory::from($standing['shootingCategory']);

                $yearSnapshot = $this->yearSnapshotRepository->findOneBy([
                    'participant'      => $participant,
                    'year'             => $year,
                    'shootingCategory' => $shootingCategory,
                ]);

                if ($yearSnapshot) {
                    $yearSnapshot->setYearPrevPosition($yearSnapshot->getYearPosition());
                    $yearSnapshot->setTotalScore($yearSnapshot->getTotalScore() + (int) $standing['totalScore']);
                    $yearSnapshot->setMeetingCount($yearSnapshot->getMeetingCount() + 1);
                } else {
                    $yearSnapshot = new YearSnapshot();
                    $yearSnapshot->setParticipant($participant);
                    $yearSnapshot->setYear($year);
                    $yearSnapshot->setShootingCategory($shootingCategory);
                    $yearSnapshot->setTotalScore((int) $standing['totalScore']);
                    $yearSnapshot->setMeetingCount(1);
                    $yearSnapshot->setYearPrevPosition(null);
                }

                $yearSnapshot->setAverageHits(
                    $yearSnapshot->getTotalScore() / $yearSnapshot->getMeetingCount()
                );
                $yearSnapshot->setLastMeeting($meeting);
                $yearSnapshot->setComputedAt(new \DateTimeImmutable());

                $this->em->persist($yearSnapshot);
            }
        }

        // ----------------------------------------------------------------
        // ÉTAPE 3 : Flush, puis calcul des positions
        // ----------------------------------------------------------------
        $this->em->flush();

        // Positions par meeting (tous types)
        $meetingSnapshots = $this->meetingSnapshotRepository->findBy(['meeting' => $meeting]);
        usort($meetingSnapshots, fn($a, $b) => $b->getScore() <=> $a->getScore());

        $lastScore = null;
        $position  = 0;

        foreach ($meetingSnapshots as $meetingSnapshot) {
            if ($lastScore === null || $meetingSnapshot->getScore() !== $lastScore) {
                $position++;
                $lastScore = $meetingSnapshot->getScore();
            }
            $meetingSnapshot->setMeetingPosition($position);
        }

        // Positions annuelles (COMPETITION uniquement)
        if ($meeting->getType() === MeetingType::COMPETITION) {
            $yearSnapshots = $this->yearSnapshotRepository->findBy(['year' => $year]);

            foreach ($yearSnapshots as $yearSnapshot) {
                $yearSnapshot->setYearPrevPosition(
                    $yearSnapshot->getYearPosition() > 0 ? $yearSnapshot->getYearPosition() : null
                );
            }

            $yearSnapshotsByCategory = [];
            foreach ($yearSnapshots as $yearSnapshot) {
                $yearSnapshotsByCategory[$yearSnapshot->getShootingCategory()->value][] = $yearSnapshot;
            }

            foreach ($yearSnapshotsByCategory as $categorySnapshots) {
                usort($categorySnapshots, static function (YearSnapshot $a, YearSnapshot $b): int {
                    $scoreComparison = $b->getTotalScore() <=> $a->getTotalScore();
                    if ($scoreComparison !== 0) {
                        return $scoreComparison;
                    }

                    $averageComparison = $b->getAverageHits() <=> $a->getAverageHits();
                    if ($averageComparison !== 0) {
                        return $averageComparison;
                    }

                    $lastNameComparison = strcmp(
                        $a->getParticipant()?->getLastName() ?? '',
                        $b->getParticipant()?->getLastName() ?? ''
                    );
                    if ($lastNameComparison !== 0) {
                        return $lastNameComparison;
                    }

                    return strcmp(
                        $a->getParticipant()?->getFirstName() ?? '',
                        $b->getParticipant()?->getFirstName() ?? ''
                    );
                });

                $lastScore = null;
                $position  = 0;

                foreach ($categorySnapshots as $yearSnapshot) {
                    if ($lastScore === null || $yearSnapshot->getTotalScore() !== $lastScore) {
                        $position++;
                        $lastScore = $yearSnapshot->getTotalScore();
                    }
                    $yearSnapshot->setYearPosition($position);
                }
            }
        }

        // Flush final pour les positions
        $this->em->flush();

        $meeting->setStatus(MeetingStatus::CLOSED);
        $meeting->setClosedAt(new \DateTimeImmutable());
        $this->meetingRepository->save($meeting, true);

        return true;
    }
}
