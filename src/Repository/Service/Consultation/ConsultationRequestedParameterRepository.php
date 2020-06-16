<?php

namespace App\Repository\Service\Consultation;

use App\Entity\Service\Consultation\ConsultationRequestedParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsultationRequestedParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultationRequestedParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultationRequestedParameter[]    findAll()
 * @method ConsultationRequestedParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRequestedParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultationRequestedParameter::class);
    }

    // /**
    //  * @return ConsultationRequestedDetail[] Returns an array of ConsultationRequestedDetail objects
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
    public function findOneBySomeField($value): ?ConsultationRequestedDetail
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
