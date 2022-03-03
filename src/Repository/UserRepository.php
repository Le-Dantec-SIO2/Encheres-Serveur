<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Enchere;
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
            ->select('u.id', 'u.email', 'u.pseudo')
            ->andWhere('u.id = :val')
            ->setParameter('val', $userId)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findUserByEmail($mail){
        return $this->createQueryBuilder('u')
            ->select('u.id','u.pseudo','u.photo','u.password')
            ->andWhere('u.email = :email')
            ->setParameter(':email',$mail)
            ->getQuery()
            ->getResult();
    }
    public function findGagnantEnchere(Enchere $enchere){
        if($enchere->getDatefin()< new \DateTime('now')){
        $maxDate = $this->createQueryBuilder('en')
            ->andWhere('en.laenchere = :laenchere')
            ->setParameter(':laenchere',$enchere)
            ->select('MAX(en.dateenchere)')
            ->getQuery()
            ->getResult();
        return $this->createQueryBuilder('en')
            ->innerJoin('en.leuser','u')
            ->andwhere('en.laenchere = :laenchere')
            ->andWhere('en.dateenchere = :ladatemax')
            ->setParameter(':laenchere',$enchere)
            ->setParameter(':ladatemax',$maxDate)
            ->select('u.pseudo','u.photo')
            ->getQuery()
            ->getResult();
        }
        else{
            return null;
        }
    }

    

    
}