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
            ->select('ms.shooterName as participant')
            ->addSelect('ms.clubName as club')
            ->addSelect('ms.year as year')
            ->addSelect('SUM(ms.totalScore) as totalPoints')
            ->join('ms.meeting', 'm')
            ->where('ms.year = :year')
            ->groupBy('ms.shooterName', 'ms.clubName', 'ms.year')  // ✅ Tout en une fois
            ->orderBy('totalPoints', 'DESC')
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
