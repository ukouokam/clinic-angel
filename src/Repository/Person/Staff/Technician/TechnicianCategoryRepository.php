<?php

namespace App\Repository\Person\Staff\Technician;

use App\Entity\Person\Staff\Technician\TechnicianCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TechnicianCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TechnicianCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TechnicianCategory[]    findAll()
 * @method TechnicianCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnicianCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TechnicianCategory::class);
    }

    // /**
    //  * @return TechnicianCategory[] Returns an array of TechnicianCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TechnicianCategory
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
