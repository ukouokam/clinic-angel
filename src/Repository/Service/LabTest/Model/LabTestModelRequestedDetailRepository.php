<?php

namespace App\Repository\Service\LabTest\Model;

use App\Entity\Service\LabTest\Model\LabTestModelRequestedDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LabTestModelRequestedDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabTestModelRequestedDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabTestModelRequestedDetail[]    findAll()
 * @method LabTestModelRequestedDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabTestModelRequestedDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabTestModelRequestedDetail::class);
    }

    // /**
    //  * @return LabTestModelRequestedDetail[] Returns an array of LabTestModelRequestedDetail objects
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
    public function findOneBySomeField($value): ?LabTestModelRequestedDetail
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
