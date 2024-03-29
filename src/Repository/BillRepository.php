<?php

namespace App\Repository;

use App\Entity\Bill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bill[]    findAll()
 * @method Bill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bill::class);
    }

    /**
     * @return Bill[] retuun un tableau de facture selon une periode trié pour un user
     */
    
    public function findByCreatedAtAndUser($YearMonth,$user)
    {
        return $this->createQueryBuilder('b')
        ->andWhere('b.createdAt LIKE :yearMonth')
        ->andWhere('b.user = :user')
        ->setParameter('yearMonth', $YearMonth.'%')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
        
    }

    
    /**
     * @return Bill[] retuun un tableau de facture selon une periode trié par status
     */
    
    public function findByCreatedAt($YearMonth)
    {
        return $this->createQueryBuilder('b')
        ->andWhere('b.createdAt LIKE :yearMonth')
        ->setParameter('yearMonth', $YearMonth.'%')
        ->orderBy('b.billState', 'ASC')
        ->getQuery()
        ->getResult();
    }

    /**
     * @return Bill[] Returns an array of Bill objects created for user with date in parameters
     */
    
    public function findByCreatedAtSupAndUser($YearMonth,$user)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.createdAt > :yearMonth')
            ->andWhere('b.user = :user')
            ->setParameter('yearMonth', $YearMonth.'%')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @return Bill[] Returns an array of Bill objects
     * @param mixed $yearMonth date à laquelle on cherche les factures format mm-aaaa
     * @param int $idBillState l'index de l etat de la facturation recherché
     */
    
    public function findByStateAndDate($yearMonth,$idBillState)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.createdAt LIKE :val')
            ->andWhere('b.billState  = :idBillState')
            ->setParameter('val', $yearMonth.'%')
            ->setParameter('idBillState', $idBillState)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Bill
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
