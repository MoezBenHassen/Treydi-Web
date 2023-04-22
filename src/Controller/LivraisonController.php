<?php

namespace App\Controller;

use App\Entity\Echange;
use App\Entity\EchangeProposer;
use App\Entity\Item;
use App\Entity\Livraison;
use App\Entity\Utilisateur;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class LivraisonController extends AbstractController
{
    #[Route('/livraison', name: 'app_livraison')]
    public function index(): Response
    {
        return $this->render('livraison/index.html.twig', [
            'controller_name' => 'LivraisonController',
        ]);
    }

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

}