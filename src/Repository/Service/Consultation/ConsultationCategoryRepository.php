<?php

namespace App\Repository\Service\Consultation;

use App\Entity\Service\Consultation\ConsultationCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsultationCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultationCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultationCategory[]    findAll()
 * @method ConsultationCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultationCategory::class);
    }

    // /**
    //  * @return ConsultationCategory[] Returns an array of ConsultationCategory objects
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
    public function findOneBySomeField($value): ?ConsultationCategory
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
