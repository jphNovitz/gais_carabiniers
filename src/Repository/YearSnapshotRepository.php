<?php

namespace App\Repository;

use App\Entity\Meeting;
use App\Entity\MeetingSnapshot;
use App\Entity\YearSnapshot;
use App\Enum\MeetingStatus;
use App\Enum\MeetingType;
use App\Enum\ShootingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<YearSnapshot>
 */
class YearSnapshotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YearSnapshot::class);
    }

    public function findSeasonStandings(int $year, ShootingCategory $shootingCategory): array
    {
        $qb = $this->createQueryBuilder('ys')
            ->join('ys.participant', 'p')
            ->select('p.id AS participantId')
            ->addSelect('p.firstName AS firstName')
            ->addSelect('p.lastName AS lastName')
            ->addSelect('p.usesSupport AS usesSupport')
            ->addSelect('ys.shootingCategory AS shootingCategory')
            ->addSelect('ys.clubName AS club')
            ->addSelect('ys.year AS year')
            ->addSelect('ys.yearPosition AS rank')
            ->addSelect('ys.yearPrevPosition AS previousRank')
            ->addSelect('ys.meetingCount AS meetingCount')
            ->addSelect('ys.totalScore AS totalScore')
            ->addSelect('ys.averageHits AS averageHits')
            ->where('ys.year = :year')
            ->andWhere('ys.shootingCategory = :shootingCategory')
            ->setParameter('year', $year)
            ->setParameter('shootingCategory', $shootingCategory);

        $rows = $qb
            ->orderBy('ys.totalScore', 'DESC')
            ->addOrderBy('ys.averageHits', 'DESC')
            ->addOrderBy('p.lastName', 'ASC')
            ->addOrderBy('p.firstName', 'ASC')
            ->getQuery()
            ->getResult();

        $rows = $this->rankRows($rows);
        $previousRanks = $this->findPreviousRanks($year, $shootingCategory);

        foreach ($rows as &$row) {
            if ($row['previousRank'] === null && isset($previousRanks[$row['participantId']])) {
                $row['previousRank'] = $previousRanks[$row['participantId']];
            }
        }
        unset($row);

        return $rows;
    }

    public function findAvailableYears(): array
    {
        return $this->createQueryBuilder('ys')
            ->select('DISTINCT ys.year')
            ->orderBy('ys.year', 'DESC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function save(YearSnapshot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function rankRows(array $rows): array
    {
        $rankedRows = [];
        $rank = 0;
        $lastScore = null;

        foreach ($rows as $row) {
            $score = $row['totalScore'];

            if ($lastScore === null || $score !== $lastScore) {
                $rank++;
                $lastScore = $score;
            }

            $row['rank'] = $rank;
            $rankedRows[] = $row;
        }

        return $rankedRows;
    }

    /**
     * Rebuilds the rank from the competition immediately preceding the latest
     * closed competition of the selected season. This also keeps the evolution
     * available for snapshots created before yearPrevPosition was populated.
     *
     * @return array<int, int> keyed by participant id
     */
    private function findPreviousRanks(int $year, ShootingCategory $shootingCategory): array
    {
        $seasonStart = new \DateTimeImmutable(sprintf('%d-01-01 00:00:00', $year));
        $seasonEnd = $seasonStart->modify('+1 year');
        $closedStatuses = [MeetingStatus::CLOSED, MeetingStatus::ARCHIVED];

        $latestMeetingDate = $this->getEntityManager()->createQueryBuilder()
            ->select('MAX(m.date)')
            ->from(Meeting::class, 'm')
            ->where('m.date >= :seasonStart')
            ->andWhere('m.date < :seasonEnd')
            ->andWhere('m.type = :competitionType')
            ->andWhere('m.status IN (:closedStatuses)')
            ->setParameter('seasonStart', $seasonStart)
            ->setParameter('seasonEnd', $seasonEnd)
            ->setParameter('competitionType', MeetingType::COMPETITION)
            ->setParameter('closedStatuses', $closedStatuses)
            ->getQuery()
            ->getSingleScalarResult();

        if ($latestMeetingDate === null) {
            return [];
        }

        $rows = $this->getEntityManager()->createQueryBuilder()
            ->select('IDENTITY(ms.participant) AS participantId')
            ->addSelect('SUM(ms.score) AS totalScore')
            ->from(MeetingSnapshot::class, 'ms')
            ->join('ms.meeting', 'm')
            ->where('m.date >= :seasonStart')
            ->andWhere('m.date < :latestMeetingDate')
            ->andWhere('m.type = :competitionType')
            ->andWhere('m.status IN (:closedStatuses)')
            ->andWhere('ms.shootingCategory = :shootingCategory')
            ->groupBy('ms.participant')
            ->setParameter('seasonStart', $seasonStart)
            ->setParameter('latestMeetingDate', new \DateTimeImmutable($latestMeetingDate))
            ->setParameter('competitionType', MeetingType::COMPETITION)
            ->setParameter('closedStatuses', $closedStatuses)
            ->setParameter('shootingCategory', $shootingCategory)
            ->getQuery()
            ->getResult();

        $previousRanks = [];
        foreach ($this->rankRows($rows) as $row) {
            $previousRanks[$row['participantId']] = $row['rank'];
        }

        return $previousRanks;
    }
}
