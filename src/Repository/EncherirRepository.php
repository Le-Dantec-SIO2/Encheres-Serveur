<?php

namespace App\Repository;

use App\Entity\Enchere;
use App\Entity\Encherir;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Id;

/**
 * @method Encherir|null find($id, $lockMode = null, $lockVersion = null)
 * @method Encherir|null findOneBy(array $criteria, array $orderBy = null)
 * @method Encherir[]    findAll()
 * @method Encherir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncherirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Encherir::class);
    }
    public function findGagnantEnchere(Enchere $enchere)
    {
        if ($enchere->getDatefin() < new \DateTime('now')) {
            $maxDate = $this->createQueryBuilder('en')
                ->andWhere('en.laenchere = :laenchere')
                ->setParameter(':laenchere', $enchere)
                ->select('MAX(en.dateenchere)')
                ->getQuery()
                ->getResult();
            return $this->createQueryBuilder('en')
                ->innerJoin('en.leuser', 'u')
                ->andwhere('en.laenchere = :laenchere')
                ->andWhere('en.dateenchere = :ladatemax')
                ->setParameter(':laenchere', $enchere)
                ->setParameter(':ladatemax', $maxDate)
                ->select('u.id, u.pseudo', 'CAST(u.photo AS NCHAR) AS photo')
                ->getQuery()
                ->getOneOrNullResult();
        } else {
            return null;
        }
    }

    public function findGagnantInverse($enchere){
        return $this->createQueryBuilder('en')
            ->innerJoin('enleuser','u')
            ->andWhere('en.laenchere = :laenchere')
            ->groupBy('en.id')
            ->select('u.id, u.pseudo, CAST(u.photo AS NCHAR) AS photo')
            ->setParameter(':laenchere',$enchere)
            ->getQuery()
            ->getOneOrNullResult();                                   
    }

    // /**
    //  * @return The actual price of the enchere given in parameters
    //  */
    public function findActualPrice($enchere){
        return $this->createQueryBuilder('en') 
            ->innerJoin('en.leuser', 'u')
            ->andWhere('en.laenchere = :enchere')
            ->orderBy('en.id','DESC')
            ->setParameter(':enchere', $enchere)
            ->select('u.id','en.prixenchere')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
        public function findOneEncherir($enchere){
        return $this->createQueryBuilder('en') 
            ->innerJoin('en.leuser', 'u')
            ->andWhere('en.laenchere = :enchere')
            ->orderBy('en.id','DESC')
            ->setParameter(':enchere', $enchere)
            ->select('u.id','en.prixenchere')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findLastSixOffer($enchere){
        return $this->createQueryBuilder('en') 
        ->innerJoin('en.leuser', 'u')
        ->andWhere('en.laenchere = :enchere')
        ->orderBy('en.id','DESC')
        ->setParameter(':enchere', $enchere)
        ->select('en.prixenchere','u.pseudo')
        ->setFirstResult(0)
        ->setMaxResults(6)
        ->getQuery()
        ->getResult();
    }
    // /**
    //  * @return Encherir[] Returns an array of Encherir objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Encherir
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}