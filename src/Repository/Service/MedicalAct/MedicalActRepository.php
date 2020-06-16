<?php

namespace App\Repository\Service\MedicalAct;

use App\Entity\Service\MedicalAct\MedicalAct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MedicalAct|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalAct|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalAct[]    findAll()
 * @method MedicalAct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalActRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalAct::class);
    }

    // /**
    //  * @return MedicalAct[] Returns an array of MedicalAct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MedicalAct
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
