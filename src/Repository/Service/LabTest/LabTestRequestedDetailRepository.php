<?php

namespace App\Repository\Service\LabTest;

use App\Entity\Service\LabTest\LabTestRequestedDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LabTestRequestedDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabTestRequestedDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabTestRequestedDetail[]    findAll()
 * @method LabTestRequestedDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabTestRequestedDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabTestRequestedDetail::class);
    }

    // /**
    //  * @return LabTestRequestedDetail[] Returns an array of LabTestRequestedDetail objects
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
    public function findOneBySomeField($value): ?LabTestRequestedDetail
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
