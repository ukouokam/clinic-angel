<?php

namespace App\Repository\Service\MedicalAct;

use App\Entity\Service\MedicalAct\MedicalActRequestedDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MedicalActRequestedDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalActRequestedDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalActRequestedDetail[]    findAll()
 * @method MedicalActRequestedDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalActRequestedDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalActRequestedDetail::class);
    }

    // /**
    //  * @return MedicalActRequestedDetail[] Returns an array of MedicalActRequestedDetail objects
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
    public function findOneBySomeField($value): ?MedicalActRequestedDetail
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
