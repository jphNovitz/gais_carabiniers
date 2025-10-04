<?php

namespace App\Repository;

use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeetingParticipant>
 */
class MeetingParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeetingParticipant::class);
    }
    public function maxPosition(Meeting $meeting): int
    {
        return (int) $this->createQueryBuilder('mp')
            ->select('COALESCE(MAX(mp.position), 0)')
            ->andWhere('mp.meeting = :m')->setParameter('m', $meeting)
            ->getQuery()->getSingleScalarResult();
    }

    public function findLast(Meeting $meeting): ?MeetingParticipant
    {
        return $this->findOneBy(['meeting' => $meeting], ['position' => 'DESC']);
    }

    //    /**
    //     * @return MeetingParticipant[] Returns an array of MeetingParticipant objects
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

    //    public function findOneBySomeField($value): ?MeetingParticipant
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
