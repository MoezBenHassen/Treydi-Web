<?php

namespace App\Repository;

use App\Entity\ViewItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ViewItems>
 *
 * @method ViewItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViewItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViewItems[]    findAll()
 * @method ViewItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewItems::class);
    }

    public function save(ViewItems $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ViewItems $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ViewItems[] Returns an array of ViewItems objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ViewItems
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function obtain($itemid,$userid): array
{
    $req =  $this->createQueryBuilder('s')
        ->andWhere('s.itemid = :val')
        ->setParameter('val', $itemid)
        ->andWhere('s.userid = :valx')
        ->setParameter('valx', $userid)
        ->getQuery()
        ->getResult();
    return $req;
}
}
