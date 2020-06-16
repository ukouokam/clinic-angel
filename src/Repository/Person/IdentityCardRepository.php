<?php

namespace App\Repository\Person;

use App\Entity\Person\IdentityCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IdentityCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdentityCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdentityCard[]    findAll()
 * @method IdentityCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentityCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IdentityCard::class);
    }

    // /**
    //  * @return IdentityCard[] Returns an array of IdentityCard objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IdentityCard
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
