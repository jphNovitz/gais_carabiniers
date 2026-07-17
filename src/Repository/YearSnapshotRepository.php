<?php

namespace App\Repository;

use App\Entity\YearSnapshot;
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

        return $this->rankRows($rows);
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
}
