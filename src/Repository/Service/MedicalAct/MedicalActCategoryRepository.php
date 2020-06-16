<?php

namespace App\Repository\Service\MedicalAct;

use App\Entity\Service\MedicalAct\MedicalActCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MedicalActCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalActCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalActCategory[]    findAll()
 * @method MedicalActCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalActCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalActCategory::class);
    }

    // /**
    //  * @return MedicalActCategory[] Returns an array of MedicalActCategory objects
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
    public function findOneBySomeField($value): ?MedicalActCategory
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
