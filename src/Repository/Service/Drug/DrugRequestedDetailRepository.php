<?php

namespace App\Repository\Service\Drug;

use App\Entity\Service\Drug\DrugRequestedDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DrugRequestedDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrugRequestedDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrugRequestedDetail[]    findAll()
 * @method DrugRequestedDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrugRequestedDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrugRequestedDetail::class);
    }

    // /**
    //  * @return DrugRequestedDetail[] Returns an array of DrugRequestedDetail objects
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
    public function findOneBySomeField($value): ?DrugRequestedDetail
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
