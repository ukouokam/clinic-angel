<?php

namespace App\Repository\Service\Consultation;

use App\Entity\Service\Consultation\ConsultationCategoryCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsultationCategoryCost|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultationCategoryCost|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultationCategoryCost[]    findAll()
 * @method ConsultationCategoryCost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationCategoryCostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultationCategoryCost::class);
    }

    // /**
    //  * @return ConsultationCategoryCost[] Returns an array of ConsultationCategoryCost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConsultationCategoryCost
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
