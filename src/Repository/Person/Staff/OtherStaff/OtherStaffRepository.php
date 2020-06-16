<?php

namespace App\Repository\Person\Staff\OtherStaff;

use App\Entity\Person\Staff\OtherStaff\OtherStaff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OtherStaff|null find($id, $lockMode = null, $lockVersion = null)
 * @method OtherStaff|null findOneBy(array $criteria, array $orderBy = null)
 * @method OtherStaff[]    findAll()
 * @method OtherStaff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OtherStaffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OtherStaff::class);
    }

    // /**
    //  * @return OtherStaff[] Returns an array of OtherStaff objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OtherStaff
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
