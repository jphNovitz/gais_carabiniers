<?php

namespace App\Repository;

use App\Entity\MeetingSnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeetingSnapshot>
 */
class MeetingSnapshotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeetingSnapshot::class);
    }

    public function findByMeetingId(int $meetingId): ?array
    {
        return $this->createQueryBuilder('ms')
            ->leftJoin('ms.meeting', 'meeting')
            ->leftJoin('ms.participant', 'participant')
            ->select('ms', 'meeting', 'participant')
            ->andWhere('ms.meeting = :meetingId')
            ->setParameter('meetingId', $meetingId)
            ->getQuery()
            ->getResult();
    }

    public function findSeasonStandings(int $year): array
    {
        return $this->createQueryBuilder('ms')
            ->join('ms.meeting', 'm')
            ->join('ms.participant', 'p')

            ->select('p.id AS participantId')
            ->addSelect('p.firstName AS firstName')
            ->addSelect('p.lastName AS lastName')
            ->addSelect('ms.clubName AS club')
            ->addSelect('ms.meetingPosition AS meetingPosition')
            ->addSelect('ms.year AS year')
            ->addSelect('SUM(ms.totalScore) AS totalScore')
            ->addSelect('COUNT(DISTINCT m.id) AS meetingCount')

            ->where('ms.year = :year')
            ->groupBy('p.id', 'p.firstName', 'p.lastName', 'ms.clubName', 'ms.year', 'ms.meetingPosition')
            ->orderBy('totalScore', 'DESC')
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
    }



    public function save(MeetingSnapshot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAvailableYears(): array
    {
        return $this->createQueryBuilder('ms')
            ->select('DISTINCT ms.year')
            ->orderBy('ms.year', 'DESC')
            ->getQuery()
            ->getSingleColumnResult();
    }
    //    /**
    //     * @return MeetingSnapshot[] Returns an array of MeetingSnapshot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MeetingSnapshot
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
