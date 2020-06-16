<?php

namespace App\Repository\Service\Drug;

use App\Entity\Service\Drug\DrugCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DrugCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrugCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrugCost[]    findAll()
 * @method DrugCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrugCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrugCost::class);
    }

    // /**
    //  * @return DrugCost[] Returns an array of DrugCost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DrugCost
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
