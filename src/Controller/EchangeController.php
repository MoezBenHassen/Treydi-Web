<?php

namespace App\Controller;


use App\Entity\EchangeProposer;
use App\Entity\Item;
use App\Entity\Echange;
use App\Entity\Utilisateur;
use App\Form\EchangeSearchTypeUser;
use App\Form\EchangeType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\NotNull;

class EchangeController extends AbstractController
{
    #[Route('/echange', name: 'app_echange')]
    public function index(): Response
    {
        return $this->render('echange/index.html.twig', [
            'controller_name' => 'EchangeController',
        ]);
    }


    //ECHANGE
    #[Route('/echange/creer', name: 'app_echange_creer')]
    public function creer(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $em = $doctrine->getManager();
        $user = $security->getUser();

        $current_date = date('Y-m-d');
        $date = new DateTime($current_date);

        $user_items = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => NULL, 'id_user' => $user->getId()]);

        $echange = new Echange();
        $form = $this->createForm(EchangeType::class, $echange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $echange->setIdUser1($user);
            $echange->setArchived(0);
            $echange->setLivEtat("Non_Accepter");
            $echange->setDateEchange($date);
            $em->persist($echange);
            $em->flush();

            $items = json_decode(json_encode($request->request->get('items')), true);

            foreach ($items as $id) {
                $idArray = json_decode($id, true);
                foreach ($idArray as $itemId) {
                    $item = $doctrine->getRepository(Item::class)->find($itemId);
                    if ($item) {
                        $item->setIdEchange($echange);
                        $em->persist($item);
                        echo "Item ID: $itemId\n";
                    }
                }
            }
            $em->flush();

            return $this->redirectToRoute('app_echange_list_mesechanges');
        }

        return $this->render('echange/user/creer.html.twig', [
            'formA' => $form->createView(),
            'user1' => $user,
            'user_items' => $user_items,
        ]);
    }

    //ADMIN
    #[Route('/echange/list', name: 'app_echangeList')]
    public function list(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Echange::class);

        $searchForm = $this->createForm(EchangeSearchTypeUser::class);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            $search = $searchForm->get('search')->getData();
            $list = $repository->SearchByTitreEchangeAdmin($search, false);
        } else {
            $list = $repository->findAll();
        }

        return $this->render('echange/admin/list.html.twig', [
            'controller_name' => 'EchangeListController',
            'list' => $list,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/echange/delete/{id}', name: 'app_echangeDelete')]
    public function remove(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Echange::class);
        $echange = $repository->find($id);


        $items = $echange->getItems();
        $items->initialize();

        foreach ($items as $item) {
            $item->setIdEchange(null);
            $em->persist($item);
        }

        $echange->setArchived(true);
        $em->persist($echange);

        $em->flush();

        return $this->redirectToRoute('app_echangeList');
    }

    #[Route('/echange/afficher/{id}', name: 'app_echange_afficher')]
    public function afficher(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $echange = $em->getRepository(Echange::class)
            ->find($id);

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

        return $this->render('echange/admin/afficher.html.twig', [
            'user1_items' => $user1_items,
            'user2_items' => $user2_items,
            'user1' => $user1,
            'user2' => $user2,
            'echange_proposer' => $echange_proposer,
        ]);
    }

    #[Route('/echange/modifier1/{id}', name: 'app_echange_modifier1')]
    public function modifier1(Request $request,ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $echange = $em->getRepository(Echange::class)
            ->find($id);

        $echange_proposer = $em
            ->getRepository(EchangeProposer::class)
            ->findBy(['id_echange' => $echange->getId(), 'archived' => false]);

        $form = $this->createForm(EchangeType::class, $echange);
        $form->remove('titre_echange');
        $form->handleRequest($request);

        $user1 = $em->getRepository(Utilisateur::class)->find($echange->getIdUser1());

        $user1_items = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => NULL, 'id_user' => $echange->getIdUser1(), 'archived' => false]);

        $user1_items_in_echange = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(),'id_user' => $echange->getIdUser1()]);

        if ($form->isSubmitted() && $form->isValid() && $echange_proposer == null) {

            $items1 = json_decode(json_encode($request->request->get('items1')), true);
            $items2 = json_decode(json_encode($request->request->get('items2')), true);

            foreach ($items1 as $id) {
                $idArray = json_decode($id, true);
                foreach ($idArray as $itemId) {
                    $item = $doctrine->getRepository(Item::class)->find($itemId);
                    if ($item) {
                        $item->setIdEchange($echange);
                        $em->persist($item);
                        echo "Item ID: $itemId\n";
                    }
                }
            }

            foreach ($items2 as $id) {
                $idArray = json_decode($id, true);
                foreach ($idArray as $itemId) {
                    $item = $doctrine->getRepository(Item::class)->find($itemId);
                    if ($item) {
                        $item->setIdEchange(null);
                        $em->persist($item);
                        echo "Item ID: $itemId\n";
                    }
                }
            }
            $em->flush();

            return $this->redirectToRoute('app_echangeList');
        }

        return $this->render('echange/admin/modifieruser1.html.twig', [
            'user1_items' => $user1_items,
            'user1_items_in_echange' => $user1_items_in_echange,
            'echange' => $echange,
            'user1' => $user1,
            'formA' => $form->createView(),
        ]);
    }

    #[Route('/echange/modifier2/{id}', name: 'app_echange_modifier2')]
    public function modifier2(Request $request,ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $echange = $em->getRepository(Echange::class)
            ->find($id);

        $user2 = $em->getRepository(Utilisateur::class)->find($echange->getIdUser2());

        $echange_proposer = $em
            ->getRepository(EchangeProposer::class)
            ->findBy(['id_echange' => $echange->getId(), 'archived' => false]);

        $form = $this->createForm(EchangeType::class, $echange);
        $form->remove('titre_echange');
        $form->handleRequest($request);

        $user2_items = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => NULL, 'id_user' => $echange->getIdUser2(), 'archived' => false]);

        $user2_items_in_echange = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(),'id_user' => $echange->getIdUser2()]);

        if ($form->isSubmitted() && $form->isValid() && $echange_proposer == null) {

            $items1 = json_decode(json_encode($request->request->get('items1')), true);
            $items2 = json_decode(json_encode($request->request->get('items2')), true);

            foreach ($items1 as $id) {
                $idArray = json_decode($id, true);
                foreach ($idArray as $itemId) {
                    $item = $doctrine->getRepository(Item::class)->find($itemId);
                    if ($item) {
                        $item->setIdEchange($echange);
                        $em->persist($item);
                        echo "Item ID: $itemId\n";
                    }
                }
            }

            foreach ($items2 as $id) {
                $idArray = json_decode($id, true);
                foreach ($idArray as $itemId) {
                    $item = $doctrine->getRepository(Item::class)->find($itemId);
                    if ($item) {
                        $item->setIdEchange(null);
                        $em->persist($item);
                        echo "Item ID: $itemId\n";
                    }
                }
            }
            $em->flush();

            return $this->redirectToRoute('app_echangeList');
        }

        return $this->render('echange/admin/modifieruser2.html.twig', [
            'user2_items' => $user2_items,
            'user2_items_in_echange' => $user2_items_in_echange,
            'echange' => $echange,
            'user2' => $user2,
            'formA' => $form->createView(),
        ]);
    }


    //USER
    #[Route('/echange/user/list', name: 'app_echange_list_user')]
    public function listUserEchange(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Echange::class);

        $itemsRep = $em->getRepository(Item::class);

        $searchForm = $this->createForm(EchangeSearchTypeUser::class);
        $searchForm->handleRequest($request);

        $list = [];

        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            $search = $searchForm->get('search')->getData();
            $searchItems = $searchForm->get('itemSearch')->getData();
            if ($search) {
                $list = $repository->SearchByTitreEchangeUser($search, false);
            } elseif($searchItems) {
                $items = $itemsRep->findBy(['libelle' => $searchItems]);

                foreach ($items as $item) {
                    $echangeId = $item->getIdEchange();
                    $echange = $repository->find($echangeId);
                    if ($echange && !$echange->isArchived() && $echange->getIdUser2() === null && !in_array($echange, $list)) {
                        $list[] = $echange;
                    }
                }
            }
        } else {
            $list = $repository->findBy(['archived' => false, 'id_user2' => null]);
        }

        return $this->render('echange/user/list.tml.twig', [
            'list' => $list,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/echange/listmesechanges', name: 'app_echange_list_mesechanges')]
    public function listMesEchanges(ManagerRegistry $doctrine, Security $security): Response
    {
        $user = $security->getUser();
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Echange::class);
        $qb = $repository->createQueryBuilder('r');

        $qb->where($qb->expr()->andX(
            $qb->expr()->eq('r.archived', ':archived'),
            $qb->expr()->orX(
                $qb->expr()->eq('r.id_user1', ':user_id'),
                $qb->expr()->eq('r.id_user2', ':user_id')
            )
        ))
            ->setParameter('archived', false)
            ->setParameter('user_id', $user->getId());

        $list = $qb->getQuery()->getResult();
        return $this->render('echange/user/mesechangelist.tml.twig', [
            'controller_name' => 'EchangeListController',
            'list' => $list
        ]);
    }

    #[Route('/echange/user/delete/{id}', name: 'app_echange_delete_user')]
    public function removeUserEchange(ManagerRegistry $doctrine, $id): Response
    {

        $em = $doctrine->getManager();
        $repository = $em->getRepository(Echange::class);
        $echange = $repository->find($id);


        $items = $echange->getItems();
        $items->initialize();

        foreach ($items as $item) {
            $item->setIdEchange(null);
            $em->persist($item);
        }


        $echange->setArchived(true);
        $em->persist($echange);

        $em->flush();
        return $this->redirectToRoute('app_echange_list_mesechanges');

    }

    #[Route('/echange/user/afficher/{id}', name: 'app_echange_user_afficher')]
    public function afficherEchangeUser(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $echange = $em->getRepository(Echange::class)
            ->find($id);

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
            ->findBy(['id_echange' => $echange->getId(), 'archived' => false]);

        return $this->render('echange/user/afficher.html.twig', [
            'user1_items' => $user1_items,
            'user2_items' => $user2_items,
            'user1' => $user1,
            'user2' => $user2,
            'echange_proposer' => $echange_proposer,
        ]);
    }
    #[Route('/echange/user/affichersans/{id}', name: 'app_echange_user_afficher_sans')]
    public function afficherEchangeUserSansProposer(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $echange = $em->getRepository(Echange::class)
            ->find($id);

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
            ->findBy(['id_echange' => $echange->getId(), 'archived' => false]);

        return $this->render('echange/user/affichersansproposer.html.twig', [
            'user1_items' => $user1_items,
            'user2_items' => $user2_items,
            'user1' => $user1,
            'user2' => $user2,
            'echange_proposer' => $echange_proposer,
            'echange' => $echange,
        ]);
    }


    //LIVREUR
    #[Route('/echange/livreur/list', name: 'app_echange_list_livreur')]
    public function listLivreurEchange(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Echange::class);
        $list = $repository->findBy(['archived' => false, 'id_user2' => 'IS NOT NULL', 'liv_etat' => 'Non_Accepter']);
        return $this->render('livraison/user/list.tml.twig', [
            'controller_name' => 'EchangeListController',
            'list' => $list
        ]);
    }


}
