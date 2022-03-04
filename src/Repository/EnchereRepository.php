<?php

namespace App\Repository;

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

    public function findEncheres($enchereId = false)
    {
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
        if ($enchereId) {
            return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere', 't')
                ->andWhere('e.datefin > :ladate')
                ->andWhere('e.id = :enchereId')
                ->setParameter(':enchereId', $enchereId)
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate', $ladate)
                ->select("e.id,DATE_FORMAT(e.datedebut,'%Y-%m-%d') AS date_debut,DATE_FORMAT(e.datefin,'%Y-%m-%d') AS date_fin,e.prixreserve,t.id AS type_enchere_id,p.id AS produit_id")
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere', 't')
                ->andWhere('e.datedebut > :ladate')
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate', $ladate)
                ->select("e.id,DATE_FORMAT(e.datedebut,'%Y-%m-%d') AS date_debut,DATE_FORMAT(e.datefin,'%Y-%m-%d') AS date_fin,e.prixreserve,t.id AS type_enchere_id,p.id AS produit_id")
                ->getQuery()
                ->getResult();
        }
    }

    public function findEncheresEnCours($enchereId = false)
    {
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');
        if ($enchereId) {
            return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere', 't')
                ->andWhere(':ladate BETWEEN e.datedebut AND e.datefin')
                ->andWhere('e.id = :enchereId')
                ->setParameter(':enchereId', $enchereId)
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate', $ladate)
                ->select("e.id,DATE_FORMAT(e.datedebut,'%Y-%m-%d') AS date_debut,DATE_FORMAT(e.datefin,'%Y-%m-%d') AS date_fin,e.prixreserve,t.id AS type_enchere_id,p.id AS produit_id")
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('e')
                ->innerjoin('e.leproduit', 'p')
                ->innerJoin('e.letypeenchere', 't')
                ->andWhere(':ladate BETWEEN e.datedebut AND e.datefin')
                ->orderBy('e.datedebut', 'ASC')
                ->setParameter('ladate', $ladate)
                ->select("e.id,DATE_FORMAT(e.datedebut,'%Y-%m-%d') AS date_debut,DATE_FORMAT(e.datefin,'%Y-%m-%d') AS date_fin,e.prixreserve,t.id AS type_enchere_id,p.id AS produit_id")
                ->getQuery()
                ->getResult();
        }
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

    public function findEnchere($enchereId = false)
    {
        $ladate = new \DateTime('now');
        $ladate = $ladate->format('Y-m-d');

        return $this->createQueryBuilder('e')
            ->innerjoin('e.leproduit', 'p')
            ->innerJoin('e.letypeenchere', 't')
            ->andWhere('e.datefin > :ladate')
            ->andWhere('e.id = :enchereId')
            ->setParameter(':enchereId', $enchereId)
            ->orderBy('e.datedebut', 'ASC')
            ->setParameter('ladate', $ladate)
            ->select("e.id,DATE_FORMAT(e.datedebut,'%Y-%m-%d') AS date_debut,DATE_FORMAT(e.datefin,'%Y-%m-%d') AS date_fin,e.prixreserve,t.id AS type_enchere_id,p.id AS produit_id")
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