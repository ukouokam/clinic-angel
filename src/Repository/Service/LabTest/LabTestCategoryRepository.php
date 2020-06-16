<?php

namespace App\Repository\Service\LabTest;

use App\Entity\Service\LabTest\LabTestCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LabTestCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabTestCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabTestCategory[]    findAll()
 * @method LabTestCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabTestCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabTestCategory::class);
    }

    // /**
    //  * @return LabTestCategory[] Returns an array of LabTestCategory objects
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
    public function findOneBySomeField($value): ?LabTestCategory
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
