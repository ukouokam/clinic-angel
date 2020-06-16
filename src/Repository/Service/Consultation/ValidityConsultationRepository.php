<?php

namespace App\Repository\Service\Consultation;

use App\Entity\Service\Consultation\ValidityConsultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ValidityConsultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValidityConsultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValidityConsultation[]    findAll()
 * @method ValidityConsultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValidityConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValidityConsultation::class);
    }

    // /**
    //  * @return ValidityConsultation[] Returns an array of ValidityConsultation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ValidityConsultation
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
