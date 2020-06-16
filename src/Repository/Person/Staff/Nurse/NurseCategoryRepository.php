<?php

namespace App\Repository\Person\Staff\Nurse;

use App\Entity\Person\Staff\Nurse\NurseCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NurseCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method NurseCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method NurseCategory[]    findAll()
 * @method NurseCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NurseCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NurseCategory::class);
    }

    // /**
    //  * @return NurseCategory[] Returns an array of NurseCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NurseCategory
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
