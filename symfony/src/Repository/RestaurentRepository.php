<?php

namespace App\Repository;

use App\Entity\Restaurent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Restaurent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurent[]    findAll()
 * @method Restaurent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurent::class);
    }

    // /**
    //  * @return Restaurent[] Returns an array of Restaurent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Restaurent
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
