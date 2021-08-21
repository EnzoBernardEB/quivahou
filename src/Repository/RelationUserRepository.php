<?php

namespace App\Repository;

use App\Entity\RelationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RelationUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method RelationUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method RelationUser[]    findAll()
 * @method RelationUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RelationUser::class);
    }

    // /**
    //  * @return RelationUser[] Returns an array of RelationUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RelationUser
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
