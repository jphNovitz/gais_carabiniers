<?php

namespace App\Repository;

use App\Entity\Meeting;
use App\Entity\MeetingParticipant;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Member>
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function qbActifsNonParticipants(Meeting $meeting): QueryBuilder
    {
        return $this->createQueryBuilder('m')
            ->leftJoin(MeetingParticipant::class, 'mp', 'WITH', 'mp.shooter = m AND mp.meeting = :meeting')
            ->andWhere('mp.id IS NULL')
            ->andWhere('m.isActive = :a')
            ->setParameter('meeting', $meeting)
            ->setParameter('a', true)
            ->orderBy('m.lastName', 'ASC')
            ->addOrderBy('m.firstName', 'ASC');
    }
    public function findLast(Meeting $meeting): Member|null
    {
        return $this->findOneBy(['meeting' => $meeting], ['position' => 'DESC']);
    }
    //    /**
    //     * @return Member[] Returns an array of Member objects
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

    //    public function findOneBySomeField($value): ?Member
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
