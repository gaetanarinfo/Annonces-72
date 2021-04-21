<?php

namespace App\Repository;

use App\Entity\Credits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Credits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Credits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Credits[]    findAll()
 * @method Credits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Credits::class);
    }

    // /**
    //  * @return Credits[] Returns an array of Credits objects
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
    public function findOneBySomeField($value): ?Credits
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
