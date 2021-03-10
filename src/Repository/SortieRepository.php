<?php

namespace App\Repository;

use App\Service\Search;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }
    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Recherche les sorties en fonction du formulaire
     * @return int|mixed|string
     */
    public function findAllSortie(Search $search)
    {
        $query= $this->createQueryBuilder('s')
            ->addOrderBy('s.dateDebut', 'DESC')
            ->join('s.campus', 'c')
            ->join('s.organisateur', 'o')
            ->select('c', 's')
            ->select('o', 's')
            //->andWhere('s.actif=true')
            ->setMaxResults(20);
        if (!empty($search->campus))
        {
            $query=$query
                ->andWhere('s.id in (:campus)')
                ->setParameter('campus', $search->campus);
        }
        if(!empty($search->searchIndice))
        {
            $query = $query
                ->andWhere('s.nom LIKE :searchIndice')
                ->setParameter('searchIndice', $search->searchIndice);
        }

        if(!empty($search->searchDateDebut))
        {
            $query = $query
                ->andWhere('s.dateDebut >= :searchDateDebut')
                ->setParameter('searchDateDebut', $search->searchDateDebut );
        }

        if(!empty($search->searchDateFin))
        {
            $query = $query
                ->andWhere('s.dateCloture <= :searchDateFin')
                ->setParameter('searchDateFin', $search->searchDateFin );
        }
        if(!empty($search->organisateur)) {
            $query = $query
                ->andWhere('s.id in(:organisateur)')
                ->setParameter('organisateur', $search->organisateur);
        }
        return $query->getQuery()->getResult();
    }
}
