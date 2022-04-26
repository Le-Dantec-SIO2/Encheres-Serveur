<?php

namespace App\Repository;

use App\Entity\PlayerFlash;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlayerFlash|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerFlash|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerFlash[]    findAll()
 * @method PlayerFlash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerFlashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerFlash::class);
    }

    // /**
    //  * @return PlayerFlash[] Returns an array of PlayerFlash objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlayerFlash
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
     public function findJoueurinscrit($value1,$value2)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.leuser', 'l')
            ->innerJoin('p.laenchere', 'u')
            ->andWhere('l.id = :val2')
            ->andWhere('u.id = :val1')
            ->orderBy('p.id','ASC')
            ->setParameter('val1', $value1)
            ->setParameter('val2', $value2)
            ->setMaxResults(1)
            ->select('p.id','l.id AS  id_user','u.id AS  id_enchere','l.pseudo', 'p.tag')

            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
     public function findJoueur($value1,$value2)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.laenchere', 'u')
            ->andWhere('p.id > :val2')
            ->andWhere('u.id = :val1')
            ->orderBy('p.id','ASC')
            ->setParameter('val1', $value1)
            ->setParameter('val2', $value2)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function findJoueurOne($value2): ?PlayerFlash
    {
        
            return $this->createQueryBuilder('p')
            ->innerJoin('p.laenchere', 'u')
            ->andWhere('u.id = :val2')
            ->orderBy('p.id','ASC')
            ->setParameter('val2', $value2)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
