<?php

namespace App\Repository;

use App\Entity\EventPlanning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventPlanning|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventPlanning|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventPlanning[]    findAll()
 * @method EventPlanning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventPlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventPlanning::class);
    }

    // /**
    //  * @return EventPlanning[] Returns an array of EventPlanning objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventPlanning
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}