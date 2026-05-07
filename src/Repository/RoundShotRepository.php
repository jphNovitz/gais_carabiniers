<?php

namespace App\Repository;

use App\Entity\Round;
use App\Entity\RoundShot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoundShot>
 */
class RoundShotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoundShot::class);
    }

    public function findRoundShotsOrderedFromLastHit(Round $round): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.meetingParticipant', 'mp')
            ->andWhere('r.round = :round')
            ->andWhere('r.leftTarget IS NOT NULL OR r.rightTarget IS NOT NULL')
            ->setParameter('round', $round)
            ->orderBy('mp.position', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Dans RoundRepository et RoundShotRepository
    public function save(object $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    //    /**
    //     * @return RoundShot[] Returns an array of RoundShot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RoundShot
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
