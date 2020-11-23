<?php

namespace App\Repository;

use App\Entity\Civilite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Civilite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Civilite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Civilite[]    findAll()
 * @method Civilite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiviliteRepository extends ServiceEntityRepository
{
    const CIVILITE_M = 1;
    const STR_CIVILITE_M = 'M';
    const CIVILITE_MME = 2;
    const STR_CIVILITE_MME = 'Mme';
    const CIVILITE_SOCIETE = 3;
    const STR_CIVILITE_SOCIETE = 'Ste';
    const CIVILITE_ASSOCIATION = 4;
    const STR_CIVILITE_ASSOCIATION = 'Assoc';
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Civilite::class);
    }

    // /**
    //  * @return Civilite[] Returns an array of Civilite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Civilite
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
