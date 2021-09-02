<?php

namespace App\Repository;

use App\Entity\MissionEnCours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MissionEnCours|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionEnCours|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionEnCours[]    findAll()
 * @method MissionEnCours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionEnCoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MissionEnCours::class);
    }

    // /**
    //  * @return MissionEnCours[] Returns an array of MissionEnCours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MissionEnCours
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
