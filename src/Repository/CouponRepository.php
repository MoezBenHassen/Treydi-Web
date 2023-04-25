<?php

namespace App\Repository;

use App\Entity\Coupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coupon>
 *
 * @method Coupon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coupon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coupon[]    findAll()
 * @method Coupon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    public function findBySearchQuery($search, $archived, $isValid, $categorie)
    {
        $query = $this->createQueryBuilder('c');

        if ($search) {
            $query->andWhere('c.title LIKE :search OR c.description LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        if ($archived !== null) {
            $query->andWhere('c.archived = :archived')
                ->setParameter('archived', $archived);
        }

        if ($isValid !== null) {
            $query->andWhere('c.isValid = :isValid')
                ->setParameter('isValid', $isValid);
        }

        if ($categorie !== null) {
            $query->join('c.categories', 'cat')
                ->andWhere('cat.id = :categorie')
                ->setParameter('categorie', $categorie);
        }

        return $query->getQuery()->getResult();
    }

    public function save(Coupon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Coupon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Coupon[] Returns an array of Coupon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Coupon
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByAllAttributes($search, $date_expiration, $description, $archived, $idCategorie)

    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.archived = :archived')
            ->setParameter('archived', $archived);

        if (!empty($search)) {
            $qb->andWhere('c.titre_coupon LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if (!empty($date_expiration)) {
            $qb->andWhere('c.date_expiration = :date_expiration')
                ->setParameter('date_expiration', $date_expiration);
        }

        if (!empty($description)) {
            $qb->andWhere('c.description_coupon LIKE :description')
                ->setParameter('description', '%' . $description . '%');
        }

        if (!empty($idCategorie)) {
            $qb->andWhere('c.id_categorie = :idCategorie')
                ->setParameter('idCategorie', $idCategorie);
        }

    

        return $qb->getQuery()->getResult();
    }

    }

