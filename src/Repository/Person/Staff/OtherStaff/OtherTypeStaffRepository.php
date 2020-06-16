<?php

namespace App\Repository\Person\Staff\OtherStaff;

use App\Entity\Person\Staff\OtherStaff\OtherTypeStaff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OtherTypeStaff|null find($id, $lockMode = null, $lockVersion = null)
 * @method OtherTypeStaff|null findOneBy(array $criteria, array $orderBy = null)
 * @method OtherTypeStaff[]    findAll()
 * @method OtherTypeStaff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OtherTypeStaffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OtherTypeStaff::class);
    }

    // /**
    //  * @return OtherTypeStaff[] Returns an array of OtherTypeStaff objects
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
    public function findOneBySomeField($value): ?OtherTypeStaff
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
