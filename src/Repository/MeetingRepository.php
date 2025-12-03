<?php

namespace App\Repository;

use App\Dto\MeetingStandingDto;
use App\Dto\ParticipantStandingDto;
use App\Entity\Meeting;
use App\Enum\MeetingType;
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
            ->select('m.id', 'm.date', 'm.label', 'm.status', 'm.type', 'm.openedAt', 'm.closedAt', 'COUNT(mp.id) AS participantCount')
            ->leftJoin('m.participants', 'mp')
            ->groupBy('m.id')
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->mapper->toDtosFromArray($results);
    }

    public function findIndexGroupedByYear(): array
    {
        $results = $this->createQueryBuilder('m')
            ->select('m.id', 'm.date', 'm.label', 'm.status', 'm.type', 'm.openedAt', 'm.closedAt', 'COUNT(mp.id) AS participantCount')
            ->leftJoin('m.participants', 'mp')
            ->groupBy('m.id')
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();

        $dtos = $this->mapper->toDtosFromArray($results);

        $grouped = [];
        foreach ($dtos as $dto) {
            $year = (int)$dto->date->format('Y');
            $grouped[$year][] = $dto;
        }

        krsort($grouped);
        $sorted = [];

        foreach ($grouped as $year => $meetings) {
            foreach ($meetings as $meeting) {
                switch ($meeting->type) {
                    case MeetingType::COMPETITION:
                        $sorted[$year]['competition'][] = $meeting;
                        break;
                    case MeetingType::PUBLIC:
                        $sorted[$year]['public'][] = $meeting;
                        break;
                    case MeetingType::OTHER:
                        $sorted[$year]['other'][] = $meeting;
                        break;
                }
            }
        }

        foreach ($sorted as &$year) {
            ksort($year);
        }
        unset($year); // sécurité

        return $sorted;

    }

    public
    function findWithParticipants(int $id): ?Meeting
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

    public
    function findWithScores($id): MeetingStandingDTO
    {
        $qb = $this->getScoresBuilder($id);

        $results = $qb->getQuery()->getResult();

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

    public
    function findSnapshot($id)
    {
        return $this->getScoresBuilder($id)->getQuery()->getResult();
    }

    public
    function save(Meeting $entity, bool $flush = false): void
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
    /**
     * @param $id
     * @return \Doctrine\ORM\QueryBuilder
     */
    public
    function getScoresBuilder($id): \Doctrine\ORM\QueryBuilder
    {
        $qb = $this->createQueryBuilder('m')
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
                // ⭐ Calcul du score en SQL
                'SUM(CASE WHEN rs.leftHit = true THEN 1 ELSE 0 END + CASE WHEN rs.rightHit = true THEN 1 ELSE 0 END) as totalScore',
                'COUNT(DISTINCT r.id) as roundsPlayed'
            )
            ->where('m.id = :id')
            ->setParameter('id', $id)
            ->groupBy('mp.id, participant.id, m.id')
            ->orderBy('totalScore', 'DESC');
        return $qb;
    }
}
