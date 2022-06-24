<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Filter\ProduitFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    // Équivalent de la fonction findAll()
    public function findTout()
    {
        return $this->createQueryBuilder('p')
            ->getQuery()
            ->getResult() // retourne un tableau d'objets (vide, 1 2 3 ....)
        ;
    }

    // Équivalent de la fonction find()
    public function findId($argument)
    {
        return $this->createQueryBuilder("p")
            ->andWhere("p.id = :marqueur")
            ->setParameter("marqueur", $argument)
            ->getQuery()
            ->getOneOrNullResult() // retourne un seul objet  ou null
        ;
    }

    public function findOrderPrix()
    {
        return $this->createQueryBuilder("p")
            ->orderBy("p.prix", "DESC") // ASC ou DESC
            ->getQuery()
            ->getResult()
        ;
    }


    public function findOrder($order)
    {
        return $this->createQueryBuilder("p")
            ->orderBy("p.prix", $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLimit()
    {
        return $this->createQueryBuilder("p")
            ->orderBy("p.prix", "DESC")
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCategorie($categorie)
    {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.categorie", "c")
            ->andWhere("c.id = :categorie")
            ->setParameter("categorie", $categorie)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCategories($categories)
    {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.categorie", "c")
            ->andWhere("c.id IN(:cat)")
            ->setParameter("cat", $categories)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMarques($marque)
    {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.marque", "m")
            ->andWhere("m.id IN(:marque)")
            ->setParameter("marque", $marque)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findMatieres($matiere)
    {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.matiere", "m")
            ->andWhere("m.id IN(:matiere)")
            ->setParameter("matiere", $matiere)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findSomething($recherche)
    {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.categorie", "c")
            ->leftJoin("p.matiere", "mt")
            ->leftJoin("p.marque", "mq")
            ->andWhere("p.titre LIKE :recherche")
            ->orWhere("p.prix LIKE :recherche")
            ->orWhere("p.description LIKE :recherche")
            ->orWhere("c.nom LIKE :recherche")
            ->orWhere("mt.nom LIKE :recherche")
            ->orWhere("mq.nom LIKE :recherche")
            ->setParameter("recherche", "%$recherche%")
            ->getQuery()
            ->getResult()
        ;
    }

    /*
        e% mot commençant par un E
        %e mot terminant par un E
        %e% mot contenant un E

    */


    /*
        BETWEEN ** AND **

    */

    public function findBetween($min, $max)
    {
        return $this->createQueryBuilder("p")
            ->andWhere("p.prix BETWEEN :min AND :max")
            ->setParameter("min", $min)
            ->setParameter("max", $max)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findPrix($min, $max)
    {
        return $this->createQueryBuilder("p")
            ->andWhere("p.prix >= :min")
            ->andWhere("p.prix <= :max")
            ->setParameter("min", $min)
            ->setParameter("max", $max)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findFiltre(ProduitFilter $filter)
    {
        dump($filter);

        $query = $this->createQueryBuilder('p')
        ->leftJoin("p.categorie", "c")
        ->leftJoin("p.marque", "mq")
        ->leftJoin("p.matiere", "mt")
        ;

        if($filter->recherche)
        {
            $query = $query
                ->andWhere("p.titre LIKE :recherche")
                ->orWhere("p.prix LIKE :recherche")
                ->orWhere("p.description LIKE :recherche")
                ->orWhere("c.nom LIKE :recherche")
                ->orWhere("mt.nom LIKE :recherche")
                ->orWhere("mq.nom LIKE :recherche")
                ->setParameter("recherche", "%$filter->recherche%")
                ;
        }

        if($filter->categories)
        {
            $query = $query
            ->andWhere("c.id IN(:categories)")
            ->setParameter("categories", $filter->categories)
            ;
        }

        if($filter->marques)
        {
            $query = $query
            ->andWhere("mq.id IN(:marques)")
            ->setParameter("marques", $filter->marques)
            ;
        }

        if($filter->matieres)
        {
            $query = $query
            ->andWhere("mt.id IN(:matieres)")
            ->setParameter("matieres", $filter->matieres)
            ;
        }
        if($filter->min)
        {
            $query = $query
                ->andWhere("p.prix >= :min")
                ->setParameter("min", $filter->min)
            ;
        }
        if($filter->max)
        {
            $query = $query
                ->andWhere("p.prix <= :max")
                ->setParameter("max", $filter->max)
            ;
        }

        if($filter->order) 
        {
     
            if($filter->order == 1)
            {
                $query = $query
                    ->orderBy("p.prix", "ASC")
                ;
            }
            if($filter->order == 2)
            {
                $query = $query
                    ->orderBy("p.prix", "DESC")
                ;
            }

            if($filter->order == 3)
            {
                $query = $query
                    ->orderBy("p.titre", "ASC")
                ;
            }
            if($filter->order == 4)
            {
                $query = $query
                    ->orderBy("p.titre", "DESC")
                ;
            }

        }
      



        
        return $query
        ->getQuery()
        ->getResult()
        ;
    }

}
