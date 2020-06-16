<?php

namespace App\Repository\Payment;

use App\Entity\Payment\AdvancePayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdvancePayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdvancePayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdvancePayment[]    findAll()
 * @method AdvancePayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvancePaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvancePayment::class);
    }

    // /**
    //  * @return AdvancePayment[] Returns an array of AdvancePayment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdvancePayment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
