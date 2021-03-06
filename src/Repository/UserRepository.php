<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

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


    public function findUserById($userId)
    {
        return $this->createQueryBuilder('u')
        ->innerJoin('u.leuser', 'u')
            ->select('u.id', 'u.email', 'u.pseudo')
            ->andWhere('u.id = :val')
            ->setParameter('val', $userId)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUserByEmailAndPass($mail, $password)
    {
        return $this->createQueryBuilder('u')
            ->select('u.id', 'u.pseudo', 'CAST(u.photo AS NCHAR) AS photo', 'u.password', 'u.email')
            ->andWhere('u.email = :email')
            ->andWhere('u.password = :password')
            ->setParameter(':email', $mail)
            ->setParameter(':password', $password)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneById($id)
    {
        return $this->createQueryBuilder('u')
            ->select('u.id', 'u.pseudo', 'CAST(u.photo AS NCHAR) AS photo', 'u.password', 'u.email')
            ->andWhere('u.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
