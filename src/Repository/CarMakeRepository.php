<?php

namespace App\Repository;

use App\Entity\CarMake;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CarMake|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarMake|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarMake[]    findAll()
 * @method CarMake[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarMakeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarMake::class);
    }

    // /**
    //  * @return CarMake[] Returns an array of CarMake objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CarMake
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
