<?php

namespace App\Repository;

use App\Dto\MeetingStandingDto;
use App\Dto\ParticipantStandingDto;
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

    public function findIndex(): array
    {
        $results = $this->createQueryBuilder('m')
            ->select('m.id', 'm.date', 'm.label', 'm.status', 'm.openedAt', 'm.closedAt', 'COUNT(mp.id) AS participantCount')
            ->leftJoin('m.participants', 'mp')
            ->groupBy('m.id')
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->mapper->toDtosFromArray($results);
    }

    public function findWithParticipants(int $id): ?Meeting
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.participants', 'p')
            ->addSelect('p')
            ->leftJoin('p.shooter', 's')
            ->addSelect('s')
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->orderBy('p.position', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findWithScores($id): MeetingStandingDTO
    {

        $results =  $this->createQueryBuilder('m')
            ->leftJoin('m.rounds', 'r')
            ->leftJoin('r.roundShots', 'rs')
            ->leftJoin('rs.meetingParticipant', 'mp')
            ->leftJoin('mp.shooter', 'participant')
            ->select(
                'm.id as meetingId',
                'm.date',
                'm.label',
                'm.status',
                'm.openedAt',
                'm.closedAt',
                'mp.id as participantId',
                'mp.position',
                'participant.id as shooterId',
                'participant.firstName',
                'participant.lastName',
                'SUM(rs.score) as totalScore',  // ⭐ Somme des scores
                'COUNT(DISTINCT r.id) as roundsPlayed'  // Bonus : nombre de rounds
            )
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->groupBy('mp.id, participant.id, m.id')  // ⭐ GROUP BY obligatoire
            ->orderBy('totalScore', 'DESC')  // Tri par score total
            ->getQuery()
            ->getResult();


        $participants = [];
        $rank = 1;
        foreach ($results as $row) {
            $participants[] = new ParticipantStandingDTO(
                participantId: $row['participantId'],
                shooterId: $row['shooterId'],
                firstName: $row['firstName'],
                lastName: $row['lastName'],
                position: $row['position'],
                totalScore: $row['totalScore'],
                roundsPlayed: $row['roundsPlayed'],
                rank: $rank++
            );
        }

        return new MeetingStandingDTO(
            meetingId: $results[0]['meetingId'],
            label: $results[0]['label'],
            date: $results[0]['date'],
            status: $results[0]['status'],
            participants: $participants
        );

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
