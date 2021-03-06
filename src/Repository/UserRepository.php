<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */

    public function findCollab($value)
    {
        $role = '"ROLE_COLLABORATEUR"';

        return $this->createQueryBuilder('u')
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
            ->andWhere('u.prenom LIKE :query OR u.nom LIKE :query')
            ->setParameter('role',$role)
            ->setParameter('query',$value)
            ->orderBy('u.nom', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    public function isAvailable($value)
    {
        $roleAdmin = '"ROLE_ADMIN"';
        $roleCommercial= '"ROLE_COMMERCIAL"';
        $roleCollaborateur = '"ROLE_COLLABORATEUR"';

        return $this->createQueryBuilder('u')
            ->andWhere('JSON_CONTAINS(u.roles, :roleCo) = 1 and JSON_CONTAINS(u.roles, :roleA) = 0 and JSON_CONTAINS(u.roles, :roleC) = 0')
            ->andWhere('u.isAvailable = :value')
            ->setParameter('roleA',$roleAdmin)
            ->setParameter('roleC',$roleCommercial)
            ->setParameter('roleCo',$roleCollaborateur)
            ->setParameter('value',$value)
            ->orderBy('u.modifDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function isRef()
    {
        $roleAdmin = '"ROLE_ADMIN"';
        $roleCommercial= '"ROLE_COMMERCIAL"';
        $roleCollaborateur = '"ROLE_COLLABORATEUR"';

        return $this->createQueryBuilder('u')
            ->andWhere('JSON_CONTAINS(u.roles, :roleCo) = 1 and JSON_CONTAINS(u.roles, :roleA) = 0 and JSON_CONTAINS(u.roles, :roleC) = 0')
            ->andWhere('u.referent is null')
            ->setParameter('roleA',$roleAdmin)
            ->setParameter('roleC',$roleCommercial)
            ->setParameter('roleCo',$roleCollaborateur)
            ->getQuery()
            ->getResult();
    }


    public function lastModif()
    {
        $role = '"ROLE_COLLABORATEUR"';

        return $this->createQueryBuilder('u')
            ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
            ->setParameter('role',$role)
            ->orderBy('u.modifDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?User
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
