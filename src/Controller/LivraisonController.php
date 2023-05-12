<?php

namespace App\Controller;

use App\Entity\Echange;
use App\Entity\EchangeProposer;
use App\Entity\Item;
use App\Entity\Livraison;
use App\Entity\Utilisateur;
use App\Repository\EchangeRepository;
use App\Repository\ItemRepository;
use App\Repository\LivraisonRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class LivraisonController extends AbstractController
{
    #[Route('/livraison/list', name: 'app_livraison_list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Livraison::class);
        $list = $repository->findall();
        return $this->render('livraison/admin/list.html.twig', [
            'list' => $list
        ]);
    }

    #[Route('/livraison/delete/{id}', name: 'app_livraison_remove')]
    public function remove(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Livraison::class);
        $livraison = $repository->find($id);

        $echange = $livraison->getIdEchange();

        $echange->setLivEtat("Non_Accepter");

        $livraison->setArchived(true);
        $em->persist($livraison);

        $em->flush();

        return $this->redirectToRoute('app_livraison_list');
    }

    #[Route('/livraison/afficher/{id}', name: 'app_livraison_afficher')]
    public function afficher(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $livraison = $doctrine->getRepository(Livraison::class)->find($id);
        $echange = $doctrine->getRepository(Echange::class)->find($livraison->getIdEchange());

        $user1 = $em->getRepository(Utilisateur::class)
            ->find($echange->getIdUser1());

        if ($echange->getIdUser2() == null) {
            $user2 = null;
        } else {
            $user2 = $em->getRepository(Utilisateur::class)
                ->find($echange->getIdUser2());
        }

        $user1_items = $em
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange->getIdUser1()]);

        $user2_items = $em
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange->getIdUser2()]);

        $echange_proposer = $em
            ->getRepository(EchangeProposer::class)
            ->findBy(['id_echange' => $echange->getId()]);

        return $this->render('livraison/admin/afficher.html.twig', [
            'user1_items' => $user1_items,
            'user2_items' => $user2_items,
            'user1' => $user1,
            'user2' => $user2,
            'echange_proposer' => $echange_proposer,
        ]);
    }

    //Livreur
    //meslivraisons
    #[Route('/livraison/livreur/list', name: 'app_livraison_livreur_list')]
    public function listLivreur(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Livraison::class);
        $list = $repository->findall();
        return $this->render('livraison/user/meslivraison.tml.twig', [
            'list' => $list
        ]);
    }

    #[Route('/livraison/user/afficher/{id}', name: 'app_livraison_user_afficher')]
    public function afficherUserLivraison(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $livraison = $doctrine->getRepository(Livraison::class)->find($id);
        $echange = $doctrine->getRepository(Echange::class)->find($livraison->getIdEchange());

        $user1 = $em->getRepository(Utilisateur::class)
            ->find($echange->getIdUser1());

        if ($echange->getIdUser2() == null) {
            $user2 = null;
        } else {
            $user2 = $em->getRepository(Utilisateur::class)
                ->find($echange->getIdUser2());
        }

        $user1_items = $em
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange->getIdUser1()]);

        $user2_items = $em
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange->getIdUser2()]);

        $echange_proposer = $em
            ->getRepository(EchangeProposer::class)
            ->findBy(['id_echange' => $echange->getId()]);

        return $this->render('livraison/user/afficher.html.twig', [
            'user1_items' => $user1_items,
            'user2_items' => $user2_items,
            'user1' => $user1,
            'user2' => $user2,
            'echange_proposer' => $echange_proposer,
        ]);
    }

    #[Route('/livraison/livreur/accepter/{id}', name: 'app_livraison_livreur_accepter')]
    public function accepterLivreur(ManagerRegistry $doctrine, Security $security, $id, MailerInterface $mailer): Response
    {
        $em = $doctrine->getManager();
        $user = $security->getUser();

        $current_date = date('Y-m-d');
        $date = new DateTime($current_date);

        $livraison = new Livraison();

        $echange = $doctrine->getRepository(Echange::class)->find($id);

        $user1 = $em->getRepository(Utilisateur::class)
            ->find($echange->getIdUser1());
        $user2 = $em->getRepository(Utilisateur::class)
            ->find($echange->getIdUser2());

        $echange->setLivEtat("Accepter");
        $em->persist($echange);
        $livraison->setIdEchange($echange);
        $livraison->setIdLivreur($user);
        $livraison->setDateCreationLivraison($date);
        $livraison->setEtatLivraison("En_Cours");
        $livraison->setArchived(false);
        $livraison->setAdresseLivraison1($user1->getAdresse());
        $livraison->setAdresseLivraison2($user2->getAdresse());
        $em->persist($livraison);

        $em->flush();

        $email = (new Email())
            ->from("treydiechange@no-reply.com")
            ->to($user->getEmail())
            ->subject("Livraison de l'Echange accepter")
            ->text("La livraison de votre Echange est accepter. Le titre de cet Echange : {$echange->getTitreEchange()}");

        $mailer->send($email);

        return $this->redirectToRoute('app_livraison_livreur_list');
    }

    //TREYDER
    #[Route('/livraison/treyder/list', name: 'app_livraison_treyder_list')]
    public function listMesLivraisonTreyder(ManagerRegistry $doctrine, Security $security): Response
    {
        $user = $security->getUser();
        $em = $doctrine->getManager();
        $repositoryLivraison = $em->getRepository(Livraison::class);

        $repositoryEchange = $em->getRepository(Echange::class);
        $qb = $repositoryEchange->createQueryBuilder('r');
        $qb->andWhere('r.archived = :archived')
            ->andWhere($qb->expr()->orX(
                'r.id_user1 = :user_id',
                'r.id_user2 = :user_id'
            ))
            ->setParameter('archived', false)
            ->setParameter('user_id', $user->getId());

        $echange = $qb->getQuery()->getResult();

        $list = $repositoryLivraison->findBy(['id_echange' => $echange]);
        return $this->render('livraison/treyder/list.html.twig', [
            'list' => $list
        ]);
    }
    
    //MOBILE
    #[Route('/livraison/addm', name: 'app_livraisonUserAddm', methods: ['GET', 'POST'])]
    public function ajouterLivraisonMobile(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $echange = $entityManager->getRepository(Echange::class)->find($request->request->get('id'));

        if (!$echange) {
            return new JsonResponse(['error' => 'Echange not found.'], Response::HTTP_NOT_FOUND);
        }

        $echange->setLivEtat("Accepter");

        $entityManager->persist($echange);

        $livraison = new Livraison();
        $em= $this->getDoctrine()->getManager();
        $date_creation   = new DateTime();
        $etat_livraison = "En_Cours";
        $livraison->setIdEchange($echange);
        $livraison->setArchived(false);
        $livraison->setDateCreationLivraison($date_creation);
        $livraison->setEtatLivraison($etat_livraison);
        $livraison->setAdresseLivraison1($echange->getIdUser1()->getAdresse());
        $livraison->setAdresseLivraison2($echange->getIdUser2()->getAdresse());
        $em->persist($livraison);
        $em->flush();

        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);
        $formatted = $serializer->serialize($livraison, 'json');
        return new JsonResponse($formatted);
    }

    #[Route('/livraison/mobile/livreur/meslivraisons', name: 'app_mes_livraisons_m', methods: ['GET', 'POST'])]
    public function mobileL(LivraisonRepository $livraisonRepository, EchangeRepository $echangeRepository, ItemRepository $itemRepository, Request $request): JsonResponse
    {
        $livraisons = $livraisonRepository->findBy(['archived' => false, 'id_livreur' => $request->request->get('id')]);

        $livraisonArray = array_map(function (Livraison $livraison) use ($echangeRepository, $itemRepository) {
            $echange = $echangeRepository->findOneBy(['id' => $livraison->getIdEchange()]);
            $echangeItems = $itemRepository->findBy(['id_echange' => $echange->getId(), 'archived' => false]);

            $date_terminer_livraison = $livraison->getDateTerminerLivraison();
            if ($date_terminer_livraison === null) {
                $date_terminer_livraison = "non terminer";
            }

            $itemsArray = array_map(function (Item $item) {
                return [
                    'id' => $item->getId(),
                    'id_echange' => $item->getIdEchange()->getId(),
                    'id_user' => $item->getIdUser()->getId(),
                    'libelle' => $item->getLibelle(),
                    'archived' => $item->isArchived(),
                ];
            }, $echangeItems);

            $echangeArray = [
                'id' => $echange->getId(),
                'titre_echange' => $echange->getTitreEchange(),
                'id_user1' => $echange->getIdUser1()->getId(),
                'id_user2' => $echange->getIdUser2()->getId()
            ];

            return [
                'id' => $livraison->getId(),
                'id_livreur' => $livraison->getIdLivreur()->getId(),
                'id_echange' => $livraison->getIdEchange()->getId(),
                'date_creation_livraison' => $livraison->getDateCreationLivraison(),
                'etat_livraison' => $livraison->getEtatLivraison(),
                'adresse_livraison1' => $livraison->getAdresseLivraison1(),
                'adresse_livraison2' => $livraison->getAdresseLivraison2(),
                'date_terminer_livraison' => $date_terminer_livraison,
                'echange' => $echangeArray,
                'echange_items' => $itemsArray,
            ];
        }, $livraisons);

        // create a JSON response containing the livraison array
        return new JsonResponse(['meslivraison' => $livraisonArray]);
    }





}
