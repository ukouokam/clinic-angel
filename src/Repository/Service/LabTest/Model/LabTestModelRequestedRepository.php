<?php

namespace App\Repository\Service\LabTest\Model;

use App\Entity\Service\LabTest\Model\LabTestModelRequested;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LabTestModelRequested|null find($id, $lockMode = null, $lockVersion = null)
 * @method LabTestModelRequested|null findOneBy(array $criteria, array $orderBy = null)
 * @method LabTestModelRequested[]    findAll()
 * @method LabTestModelRequested[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabTestModelRequestedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabTestModelRequested::class);
    }

    // /**
    //  * @return LabTestModelRequested[] Returns an array of LabTestModelRequested objects
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
    public function findOneBySomeField($value): ?LabTestModelRequested
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
