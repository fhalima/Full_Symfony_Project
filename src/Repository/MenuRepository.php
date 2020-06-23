<?php

namespace App\Repository;

use App\Entity\MenuDetaille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MenuDetaille|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuDetaille|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuDetaille[]    findAll()
 * @method MenuDetaille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuDetailleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuDetaille::class);
    }

    // /**
    //  * @return MenuDetaille[] Returns an array of MenuDetaille objects
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
    public function findOneBySomeField($value): ?MenuDetaille
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
