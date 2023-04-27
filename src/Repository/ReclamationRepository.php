<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;





/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }
    public function returnall(){
        $qd =  $this->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->setParameter('archived', false);

        return  $qd->getQuery()->getResult();

    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function compterReclamationsParMois()
    {
        $em = $this->getEntityManager();
        $query = $this->createQueryBuilder('r')
            ->select("MONTH(r.date_creation) as mois, YEAR(r.date_creation) as annee, COUNT(r) as nb_reclamations")
            ->groupBy('mois, annee')
            ->getQuery();

        return $resultats = $query->getResult();
    }

    public function compterReclamationsParMoisTraité()
    {
        $em = $this->getEntityManager();
        $query = $this->createQueryBuilder('r')
            ->where('r.etat_reclamation = :etat')
            ->setParameter('etat', "Traité")
            ->select("MONTH(r.date_creation) as mois, YEAR(r.date_creation) as annee, COUNT(r) as nb_reclamations")
            ->groupBy('mois, annee')
            ->getQuery();

        return $resultats = $query->getResult();
    }


        public function findByidreclamation(int $id){
             $qd =  $this->createQueryBuilder('r')
                ->where('r.id = :id ')
                 ->andWhere('r.archived = :archived')
                 ->setParameter('id',$id)
            ->setParameter('archived', false);

            return  $qd->getQuery()->getResult();

    }

    public function findByTitreEtDescriptionEtDateCreation(bool $archived, string $search = null, string $search2 = null, string $dateCreation = null,String $etatReclamation =null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->setParameter('archived', $archived);

        if ($search) {
            $qb->andWhere('SOUNDEX(r.titre_reclamation) = SOUNDEX(:search) OR r.titre_reclamation LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        if ($search2) {
            $qb->andWhere('SOUNDEX(r.description_reclamation) = SOUNDEX(:search2) OR r.description_reclamation LIKE :search2')
                ->setParameter('search2', '%'.$search2.'%');
        }
        if ($dateCreation) {
            $qb->andWhere('r.date_creation = :dateCreation')
                ->setParameter('dateCreation', new \DateTime($dateCreation));
        }
        if ($etatReclamation) {
            $qb->andWhere('r.etat_reclamation = :etatReclamation')
                ->setParameter('etatReclamation', $etatReclamation);
        }



        return $qb->getQuery()->getResult();
    }

    public function findByTitreEtDescriptionEtDateCreationUser(bool $archived, string $search = null, string $search2 = null, string $dateCreation = null,String $etatReclamation =null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->setParameter('archived', $archived);

        if ($search) {
            $qb->andWhere('SOUNDEX(r.titre_reclamation) = SOUNDEX(:search) OR r.titre_reclamation LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        if ($search2) {
            $qb->andWhere('SOUNDEX(r.description_reclamation) = SOUNDEX(:search2) OR r.description_reclamation LIKE :search2')
                ->setParameter('search2', '%'.$search2.'%');
        }
        if ($dateCreation) {
            $qb->andWhere('r.date_creation = :dateCreation')
                ->setParameter('dateCreation', new \DateTime($dateCreation));
        }
        if ($etatReclamation) {
            $qb->andWhere('r.etat_reclamation = :etatReclamation')
                ->setParameter('etatReclamation', $etatReclamation);
        }

        $qb->andWhere('r.id_user = :idu')
            ->setParameter('idu', 1);

        return $qb->getQuery()->getResult();
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
