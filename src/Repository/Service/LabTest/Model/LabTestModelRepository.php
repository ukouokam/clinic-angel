<?php

namespace App\Repository\Service\LabTest\Model;

use App\Entity\Service\LabTest\Model\LabTestModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LabTestModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabTestModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabTestModel[]    findAll()
 * @method LabTestModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabTestModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabTestModel::class);
    }

    // /**
    //  * @return LabTestModel[] Returns an array of LabTestModel objects
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
    public function findOneBySomeField($value): ?LabTestModel
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
