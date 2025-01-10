<?php

namespace App\Repository;

use App\Entity\FacebookEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Club>
 */
class FacebookEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookEvent::class);
    }

    public function findLastFutureElements($limit = 1): ?array
    {
        return $this->createQueryBuilder('f')
            ->where('f.date > :now')      // Filtrer les dates dans le futur
            ->setParameter('now', new \DateTime()) // Définir le paramètre actuel
            ->orderBy('f.date', 'ASC')    // Trier par date ascendante (le plus proche du futur en premier)
            ->setMaxResults($limit)            // Limiter à 1 résultat
            ->getQuery()
            ->getResult();       // Retourner l'entité ou null si aucun résultat
    }
}
