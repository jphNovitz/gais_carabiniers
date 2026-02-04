<?php

namespace App\Repository;

use App\Entity\FacebookEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FacebookEvent>
 */
class FacebookEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookEvent::class);
    }

    public function findLastFutureElements($limit = 5): ?array
    {
        return $this->createQueryBuilder('f')
            ->where('f.date >= :now')      // Filtrer les dates dans le futur
            ->setParameter('now', new \DateTime('today midnight')) // Définir le paramètre actuel
            ->orderBy('f.date', 'DESC')   
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    public function findNextFutureElement(): ?FacebookEvent
    {
        $tz = new \DateTimeZone('Europe/Brussels');
        $now = (new \DateTimeImmutable('now', $tz))->setTime(0, 0, 0);

        return $this->createQueryBuilder('f')
            ->andWhere('f.date >= :now')
            ->setParameter('now', $now)
            ->orderBy('f.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findAllFutureElements(): ?array
    {
        return $this->createQueryBuilder('f')
            ->where('f.date >= :now')      // Filtrer les dates dans le futur
            ->setParameter('now', new \DateTime('today midnight'))
            ->orderBy('f.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findAllPastElements(): ?array
    {
        return $this->createQueryBuilder('f')
            ->where('f.date < :now')      // Filtrer les dates dans le futur
            ->setParameter('now', new \DateTime())
            ->orderBy('f.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
