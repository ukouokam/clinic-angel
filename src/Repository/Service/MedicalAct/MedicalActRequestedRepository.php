<?php

namespace App\Repository\Service\MedicalAct;

use App\Entity\Service\MedicalAct\MedicalActRequested;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MedicalActRequested|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalActRequested|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalActRequested[]    findAll()
 * @method MedicalActRequested[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalActRequestedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalActRequested::class);
    }

    // /**
    //  * @return MedicalActRequested[] Returns an array of MedicalActRequested objects
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
    public function findOneBySomeField($value): ?MedicalActRequested
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
