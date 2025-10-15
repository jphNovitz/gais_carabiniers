<?php

namespace App\Repository;

use App\Entity\Meeting;
use App\Mapper\MeetingMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meeting>
 */
class MeetingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private MeetingMapper $mapper)
    {
        parent::__construct($registry, Meeting::class);
    }
//'m', 'COUNT(m.mp.id) AS participantCount'
    public function findIndex(): array
    {
        $results =  $this->createQueryBuilder('m')
            ->select('m.id', 'm.date', 'm.label', 'm.status', 'm.openedAt', 'm.closedAt', 'COUNT(mp.id) AS participantCount')
            ->leftJoin('m.participants', 'mp')
            ->groupBy('m.id')
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->mapper->toDtosFromArray($results);
    }
    public function save(Meeting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Meeting[] Returns an array of Meeting objects
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

    //    public function findOneBySomeField($value): ?Meeting
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
