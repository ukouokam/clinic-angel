<?php

namespace App\Repository\Service\LabTest\Model;

use App\Entity\Service\LabTest\Model\LabTestModelDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LabTestModelDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabTestModelDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabTestModelDetail[]    findAll()
 * @method LabTestModelDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabTestModelDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabTestModelDetail::class);
    }

    // /**
    //  * @return LabTestModelDetail[] Returns an array of LabTestModelDetail objects
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
    public function findOneBySomeField($value): ?LabTestModelDetail
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
