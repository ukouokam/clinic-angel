<?php

namespace App\Repository\Service\Consultation;

use App\Entity\Service\Consultation\ConsultationRequested;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsultationRequested|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultationRequested|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultationRequested[]    findAll()
 * @method ConsultationRequested[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRequestedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultationRequested::class);
    }

    // /**
    //  * @return ConsultationRequested[] Returns an array of ConsultationRequested objects
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
    public function findOneBySomeField($value): ?ConsultationRequested
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
