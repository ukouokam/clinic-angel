<?php

namespace App\Repository\Person\Staff\Doctor;

use App\Entity\Person\Staff\Doctor\DoctorCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DoctorCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctorCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctorCategory[]    findAll()
 * @method DoctorCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorCategory::class);
    }

    // /**
    //  * @return DoctorCategory[] Returns an array of DoctorCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DoctorCategory
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
