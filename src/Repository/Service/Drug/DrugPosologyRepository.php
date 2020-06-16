<?php

namespace App\Repository\Service\Drug;

use App\Entity\Service\Drug\DrugPosology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DrugPosology|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrugPosology|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrugPosology[]    findAll()
 * @method DrugPosology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrugPosologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrugPosology::class);
    }

    // /**
    //  * @return DrugPosology[] Returns an array of DrugPosology objects
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
    public function findOneBySomeField($value): ?DrugPosology
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
