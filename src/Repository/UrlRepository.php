<?php

namespace App\Repository;

use App\Entity\Url;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Url|null find($id, $lockMode = null, $lockVersion = null)
 * @method Url|null findOneBy(array $criteria, array $orderBy = null)
 * @method Url[]    findAll()
 * @method Url[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Url::class);
    }


    public function findByIdJoinUrls(int $userid): ?Url
    {
        $qb = $this->createQueryBuilder('link');
        $qb->innerJoin('App\Entity\User','u',Join::WITH, 'u.id = link.user_id');
        dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getOneOrNullResult();
    }


//    public function findByIdJoinUrls(int $userid): ?Url
//    {
//        $qb = $this->createQueryBuilder('link');
//        $qb->innerJoin('App\Entity\User','u',Join::WITH, 'u = link.user_id');
//        dump($qb->getQuery()->getResult());
//        return $qb->getQuery()->getOneOrNullResult();
//    }

    // /**
    //  * @return Url[] Returns an array of Url objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Url
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
