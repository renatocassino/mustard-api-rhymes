<?php

namespace App\Repository;

use App\Entity\Lyric;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Lyric|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lyric|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lyric[]    findAll()
 * @method Lyric[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LyricRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lyric::class);
    }

    // /**
    //  * @return Lyric[] Returns an array of Lyric objects
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
    public function findOneBySomeField($value): ?Lyric
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
