<?php

namespace App\Repository;

use App\Entity\BillState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BillState|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillState|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillState[]    findAll()
 * @method BillState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillState::class);
    }

    // /**
    //  * @return BillState[] Returns an array of BillState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BillState
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
