<?php

namespace App\Repository;

use App\Entity\LikeItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LikeItems>
 *
 * @method LikeItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeItems[]    findAll()
 * @method LikeItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikeItems::class);
    }

    public function save(LikeItems $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LikeItems $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return LikeItems[] Returns an array of LikeItems objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LikeItems
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
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
