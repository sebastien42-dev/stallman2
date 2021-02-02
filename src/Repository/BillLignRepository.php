<?php

namespace App\Repository;

use App\Entity\BillLign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BillLign|null find($id, $lockMode = null, $lockVersion = null)
 * @method BillLign|null findOneBy(array $criteria, array $orderBy = null)
 * @method BillLign[]    findAll()
 * @method BillLign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillLignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BillLign::class);
    }

    // /**
    //  * @return BillLign[] Returns an array of BillLign objects
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
    public function findOneBySomeField($value): ?BillLign
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
