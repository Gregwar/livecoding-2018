<?php

namespace App\Repository;

use App\Entity\LastView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LastView|null find($id, $lockMode = null, $lockVersion = null)
 * @method LastView|null findOneBy(array $criteria, array $orderBy = null)
 * @method LastView[]    findAll()
 * @method LastView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LastViewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LastView::class);
    }

    // /**
    //  * @return LastView[] Returns an array of LastView objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LastView
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
