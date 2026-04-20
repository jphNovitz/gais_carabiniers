<?php

namespace App\Repository;

use App\Entity\YearSnapshot;
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

    public function findSeasonStandings(int $year): array
    {
        return $this->createQueryBuilder('ys')
            ->join('ys.participant', 'p')
            ->select('p.id AS participantId')
            ->addSelect('p.firstName AS firstName')
            ->addSelect('p.lastName AS lastName')
            ->addSelect('p.usesSupport AS usesSupport')
            ->addSelect('ys.clubName AS club')
            ->addSelect('ys.year AS year')
            ->addSelect('ys.yearPosition AS rank')
            ->addSelect('ys.yearPrevPosition AS previousRank')
            ->addSelect('ys.meetingCount AS meetingCount')
            ->addSelect('ys.totalScore AS totalScore')
            ->addSelect('ys.averageHits AS averageHits')
            ->where('ys.year = :year')
            ->orderBy('ys.yearPosition', 'ASC')
            ->addOrderBy('ys.totalScore', 'DESC')
            ->addOrderBy('p.lastName', 'ASC')
            ->addOrderBy('p.firstName', 'ASC')
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
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
}
