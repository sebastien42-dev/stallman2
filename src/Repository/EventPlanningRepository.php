<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\EventPlanning;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    /**
     * @return EventPlanning[] Returns an array of EventPlanning objects
     */
    
    public function findEventsByUser(User $user)
    {
        return $this->createQueryBuilder('e')
            ->join('e.classes','classe')
            ->join('classe.users','user')
            ->andWhere('user = :val')
            ->setParameter('val', $user)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

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
