<?php

namespace App\Repository;

use App\Entity\UserHasCompetence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserHasCompetence|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserHasCompetence|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserHasCompetence[]    findAll()
 * @method UserHasCompetence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserHasCompetenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserHasCompetence::class);
    }

    // /**
    //  * @return UserHasCompetence[] Returns an array of UserHasCompetence objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserHasCompetence
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
