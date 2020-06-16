<?php

namespace App\Repository\Service\Drug;

use App\Entity\Service\Drug\DrugForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DrugForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrugForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrugForm[]    findAll()
 * @method DrugForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrugFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrugForm::class);
    }

    // /**
    //  * @return DrugForm[] Returns an array of DrugForm objects
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
    public function findOneBySomeField($value): ?DrugForm
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
