<?php

namespace App\Repository\Service\LabTest;

use App\Entity\Service\LabTest\LabTestCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LabTestCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabTestCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabTestCost[]    findAll()
 * @method LabTestCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabTestCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabTestCost::class);
    }

    // /**
    //  * @return LabTestCost[] Returns an array of LabTestCost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LabTestCost
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
