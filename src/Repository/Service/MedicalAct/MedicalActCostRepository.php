<?php

namespace App\Repository\Service\MedicalAct;

use App\Entity\Service\MedicalAct\MedicalActCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MedicalActCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalActCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalActCost[]    findAll()
 * @method MedicalActCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalActCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalActCost::class);
    }

    // /**
    //  * @return MedicalActCost[] Returns an array of MedicalActCost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MedicalActCost
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
