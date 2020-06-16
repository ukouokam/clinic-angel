<?php

namespace App\Repository\Person\Staff\Nurse;

use App\Entity\Person\Staff\Nurse\Nurse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nurse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nurse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nurse[]    findAll()
 * @method Nurse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NurseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nurse::class);
    }

    // /**
    //  * @return Nurse[] Returns an array of Nurse objects
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
    public function findOneBySomeField($value): ?Nurse
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
