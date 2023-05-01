<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void

    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function saveA(Article $entity, Utilisateur $user , bool $flush = false): void
    {
        $entity->setArchived(false);
        //set the idUser to the current livreur
        /*$entity->setIdUser($this->getEntityManager()->getRepository(Utilisateur::class)->findOneBy(['id' => $this->getUser()->getId()]));*/
        $entity->setIdUser($this->getEntityManager()->getRepository(Utilisateur::class)->findOneBy(['id' => $user->getId()]));
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    //removeA function that updated the archived field to treu
    public function removeA(Article $entity, bool $flush = false): void
    {
        $entity->setArchived(true);
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /*function findByQ that uses query builder to find by categorie and archived*/
    public function findArticlesByCategory($categoryId):QueryBuilder
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where('a.id_categorie = :categoryId')
            ->andWhere('a.archived = :archived')
            ->setParameter('categoryId', $categoryId)
            ->setParameter('archived', false);

        return $qb;
    }

    public function findByTitleAndDescriptionAndDateI(string $search=null, ?string $date_publication = null, bool $archived){
        $queryBuilder= $this->createQueryBuilder('a');
        $queryBuilder->where('a.archived = :archived');
        $queryBuilder->setParameter('archived', $archived);
        if($search){
            $queryBuilder->andWhere('a.titre LIKE :search OR a.description LIKE :search');
            $queryBuilder->setParameter('search', '%'.$search.'%');
        }

        if ($date_publication) {
            $date = new \DateTime($date_publication);
            $dateFormatted = $date->format('Y-m-d');
            $queryBuilder->andWhere("DATE_FORMAT(a.date_publication, '%Y-%m-%d') = :dateCreation");
            $queryBuilder->setParameter('dateCreation', $dateFormatted);
        }

        $queryBuilder->orderBy('a.id', 'ASC');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findByTitleAndDescriptionAndDate(string $search=null, ?string $date_publication = null, bool $archived):QueryBuilder{
        $queryBuilder= $this->createQueryBuilder('a');
        $queryBuilder->where('a.archived = :archived');
        $queryBuilder->setParameter('archived', $archived);
        if($search){
            $queryBuilder->andWhere('a.titre LIKE :search OR a.description LIKE :search');
            $queryBuilder->setParameter('search', '%'.$search.'%');
        }

        if ($date_publication) {
            $date = new \DateTime($date_publication);
            $dateFormatted = $date->format('Y-m-d');
            $queryBuilder->andWhere("DATE_FORMAT(a.date_publication, '%Y-%m-%d') = :dateCreation");
            $queryBuilder->setParameter('dateCreation', $dateFormatted);
        }

        $queryBuilder->orderBy('a.id', 'ASC');
        return $queryBuilder;
    }

    public function findByArchived(bool $archived):QueryBuilder{
        $queryBuilder= $this->createQueryBuilder('a');
        $queryBuilder->where('a.archived = :archived');
        $queryBuilder->setParameter('archived', $archived);
        $queryBuilder->orderBy('a.id', 'ASC');
        return $queryBuilder;
    }
    public function findByCategory($cat):QueryBuilder{
        $queryBuilder= $this->createQueryBuilder('a');
        $queryBuilder->where('a.idCategorie = :cat');
        $queryBuilder->setParameter('cat', $cat);
        $queryBuilder->orderBy('a.id', 'ASC');
        return $queryBuilder;
    }
//    /**
//     * @return Article[] Returns an array of Article objects
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

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
