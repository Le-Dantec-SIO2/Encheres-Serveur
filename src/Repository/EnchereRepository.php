<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Enchere;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Enchere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enchere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enchere[]    findAll()
 * @method Enchere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnchereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enchere::class);
    }

    // /**
    //  * @return Enchere[] Returns an array of Enchere objects
    //  */

    public function findEncheres()
    {
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
        return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere','t')
                ->andWhere('e.datefin > :ladate')
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate',$ladate)
                ->getQuery()
                ->getResult()
            ;

    }
    public function findEncheresByType($IdTypeEnchere)
    {
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
        return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere','t')
                ->andWhere('e.datefin > :ladate')
                ->andWhere('t.id = :IdTypeEnchere')
                ->setParameter(':IdTypeEnchere',$IdTypeEnchere)
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate',$ladate)
                ->getQuery()
                ->getResult()
            ;

    }
    public function findEnchere($enchereId)
    {
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
            return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere','t')
                
                ->andWhere('e.datefin > :ladate')
                ->andWhere('e.id = :enchereId')
                ->setParameter(':enchereId',$enchereId)
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate',$ladate)
                ->getQuery()
                ->getOneOrNullResult()
            ;

    }

    public function findEncheresEnCours()
    {
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
        return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere', 't')
                ->andWhere(':ladate BETWEEN e.datedebut AND e.datefin')
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate', $ladate)
                ->getQuery()
                ->getResult();
    }

    public function findEncheresInverseesFinies(){
        return $this->createQueryBuilder('e')
                ->leftJoin('e.lesencherirs','en')
                ->innerJoin('e.letypeenchere','t')
                ->andWhere('t.id = 2')
                ->groupBy('e.id')
                ->having('COUNT(en.id)>0')
                ->select('e.id')
                ->getQuery()
                ->getResult();
    }

    public function findEncheresEnCoursByType($IdTypeEnchere){
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
            return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere', 't')
                ->andWhere(':ladate BETWEEN e.datedebut AND e.datefin')
                ->andWhere('t.id = :IdTypeEnchere')
                ->setParameter(':IdTypeEnchere', $IdTypeEnchere)
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate', $ladate)
                ->getQuery()
                ->getResult();
    }

    public function findEnchereEnCours($enchereId)
    {
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
            return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere', 't')
                ->andWhere(':ladate BETWEEN e.datedebut AND e.datefin')
                ->andWhere('e.id = :enchereId')
                ->setParameter(':enchereId', $enchereId)
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate', $ladate)
                ->getQuery()
                ->getResult();

    }

    public function findEncheresFutures(){
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
        return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere', 't')
                ->andWhere(':ladate < e.datedebut')
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate', $ladate)
                ->getQuery()
                ->getResult();
    }

    public function findEncheresParticipes($userId)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.leproduit', 'p')
            ->innerJoin('e.letypeenchere', 't')
            ->innerJoin('e.lesencherirs', 'en')
            ->innerJoin('en.leuser', 'u')
            ->andWhere('u.id = :userID')
            ->orderBy('e.datedebut', 'ASC')
            ->setParameter(':userID', $userId)
            ->select("e.id,DATE_FORMAT(e.datedebut,'%Y-%m-%d') AS date_debut,DATE_FORMAT(e.datefin,'%Y-%m-%d') AS date_fin,e.prixreserve,t.id AS type_enchere_id, p.id AS produit_id")
            ->getQuery()
            ->getResult();
    }

    public function findEncheresAll()
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.leproduit', 'p')
            ->innerJoin('e.letypeenchere', 't')
            ->innerJoin('e.lesencherirs', 'en')
            ->innerJoin('en.leuser', 'u')
            ->orderBy('e.datedebut', 'ASC')
            ->select("e.id,DATE_FORMAT(e.datedebut,'%Y-%m-%d') AS date_debut,DATE_FORMAT(e.datefin,'%Y-%m-%d') AS date_fin,e.prixreserve,t.id AS type_enchere_id, p.id AS produit_id")
            ->getQuery()
            ->getResult();
    }

     public function findEnchereTestObjet()
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.leproduit', 'p')
            ->innerJoin('e.letypeenchere', 't')
            ->innerJoin('e.lesencherirs', 'en')
            ->innerJoin('en.leuser', 'u')
            ->orderBy('e.datedebut', 'ASC')
            ->select("e.id,DATE_FORMAT(e.datedebut,'%Y-%m-%d') AS date_debut,DATE_FORMAT(e.datefin,'%Y-%m-%d') AS date_fin,e.prixreserve,t.id AS type_enchere_id, p.id AS produit_id, MAX(en.prixenchere) AS enchereactuelle")
            ->groupBy('e.id')
            ->getQuery()
            ->getResult();
            }
    
    public function findProchaineEnchere(){
        return $this->createQueryBuilder('e')
            ->innerjoin('e.leproduit', 'p')
            ->innerJoin('e.letypeenchere', 't')
            ->andWhere('e.datedebut < CURRENT_DATE()')
            ->orderBy('DATE_DIFF( e.datedebut, CURRENT_TIMESTAMP())')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /*
    public function findOneBySomeField($value): ?Enchere
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // /**
    //  * @return Enchere[] Returns an array of Enchere objects
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
    public function findOneBySomeField($value): ?Enchere
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