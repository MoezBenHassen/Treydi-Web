<?php

namespace App\Repository;

use App\Entity\ArticleRatings;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleRatings>
 *
 * @method ArticleRatings|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleRatings|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleRatings[]    findAll()
 * @method ArticleRatings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRatingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleRatings::class);
    }

    public function getAvgRating (int $id_article): float
    {
        $query = $this->createQueryBuilder('a')
            ->select('AVG(a.rating) as avg_rating')
            ->where('a.id_article = :id_article')
            ->setParameter('id_article', $id_article)
            ->getQuery();

        $result = $query->getOneOrNullResult();

        return $result['avg_rating'];
    }
    public function save(ArticleRatings $entity, bool $flush = false): void
    {
        /*check if the livreur already voted*/
        $query = $this->createQueryBuilder('a')
            ->where('a.id_article = :id_article')
            ->andWhere('a.id_user = :id_user')
            ->setParameter('id_article', $entity->getIdArticle()->getId())
            ->setParameter('id_user', $entity->getIdUser()->getId())
            ->getQuery();

        $result = $query->getOneOrNullResult();

        if ($result) {
            $result->setRating($entity->getRating());
            $entity = $result;
        }

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ArticleRatings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ArticleRatings[] Returns an array of ArticleRatings objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ArticleRatings
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
