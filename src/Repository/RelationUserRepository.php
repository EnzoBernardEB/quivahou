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


    //  * @return RelationUser[] Returns an array of RelationUser objects
    //  */
    public function getMyPendingRelRequest($userID,)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.requestUser = :val')
            ->andWhere('r.pending = true')
            ->andWhere('r.isAccepted = false')
            ->andWhere('r.isDeny = false')
            ->setParameter('val', $userID)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getRelRequest($userID)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->andWhere('r.pending = true')
            ->andWhere('r.isAccepted = false')
            ->andWhere('r.isDeny = false')
            ->setParameter('val', $userID)
            ->getQuery()
            ->getResult()
            ;
    }
    public function myCollegue($userID)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val ')
            ->andWhere('r.pending = false')
            ->andWhere('r.isAccepted = true')
            ->andWhere('r.isDeny = false')
            ->setParameter('val', $userID)
            ->getQuery()
            ->getResult()
            ;
    }
    public function acceptRequest($userID,$myID)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :myID')
            ->andWhere('r.requestUser = :hisID')
            ->andWhere('r.pending = true')
            ->andWhere('r.isAccepted = false')
            ->andWhere('r.isDeny = false')
            ->setParameter('myID', $myID)
            ->setParameter('hisID', $userID)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }
    public function canSee($userID)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val or r.requestUser = :val')
            ->andWhere('r.pending = false')
            ->andWhere('r.isAccepted = true')
            ->andWhere('r.isDeny = false')
            ->setParameter('val', $userID)
            ->getQuery()
            ->getResult()
            ;
    }



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
