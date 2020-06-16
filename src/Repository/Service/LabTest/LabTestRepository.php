<?php

namespace App\Repository\Service\LabTest;

use App\Entity\Service\LabTest\LabTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LabTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabTest[]    findAll()
 * @method LabTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabTest::class);
    }

    // /**
    //  * @return LabTest[] Returns an array of LabTest objects
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
    public function findOneBySomeField($value): ?LabTest
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
