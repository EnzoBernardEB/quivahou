<?php

namespace App\Repository;

use App\Entity\AdressTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdressTemp|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdressTemp|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdressTemp[]    findAll()
 * @method AdressTemp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdressTempRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdressTemp::class);
    }

    // /**
    //  * @return AdressTemp[] Returns an array of AdressTemp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdressTemp
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
