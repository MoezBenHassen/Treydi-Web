<?php

namespace App\Controller;


use App\Entity\EchangeProposer;
use App\Entity\Item;
use App\Entity\Echange;
use App\Entity\Utilisateur;
use App\Form\EchangeSearchTypeUser;
use App\Form\EchangeType;
use App\Repository\EchangeRepository;
use App\Repository\ItemRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EchangeController extends AbstractController
{
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
            $user = $security->getUser();
            $user->setScore($user->getScore() + 1000);
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

    #[Route('/echange/user/modifier/{id}', name: 'app_echange_user_modifier')]
    public function UserModifier(Request $request,ManagerRegistry $doctrine, $id): Response
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

            return $this->redirectToRoute('app_echange_list_mesechanges');
        }

        return $this->render('echange/user/modifier.html.twig', [
            'user1_items' => $user1_items,
            'user1_items_in_echange' => $user1_items_in_echange,
            'echange' => $echange,
            'user1' => $user1,
            'echange_proposer' => $echange_proposer,
            'formA' => $form->createView(),
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

        $qb->andWhere('r.archived = :archived')
            ->andWhere($qb->expr()->orX(
                'r.id_user1 = :user_id',
                'r.id_user2 = :user_id'
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
        $list = $repository->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->andWhere('r.id_user2 IS NOT NULL')
            ->andWhere('r.liv_etat = :liv_etat')
            ->setParameters([
                'archived' => false,
                'liv_etat' => 'Non_Accepter',
            ])
            ->getQuery()
            ->getResult();
        return $this->render('livraison/user/list.tml.twig', [
            'controller_name' => 'EchangeListController',
            'list' => $list
        ]);
    }
    
     //MOBILE
    #[Route('/echange/mobile/list', name: 'app_echangeList_m', methods: ['GET', 'POST'])]
    public function mobileL(EchangeRepository $repository, ItemRepository $itemsRep): JsonResponse
    {
        $list = $repository->findBy(['archived' => false]);

        $echangesArray = array_map(function (Echange $echange) use ($itemsRep) {
            $echangeItems = $itemsRep->findBy(['id_echange' => $echange->getId(), 'archived' => false]);

            $itemsArray = array_map(function (Item $item) {
                return [
                    'id' => $item->getId(),
                    'id_echange' => $item->getIdEchange()->getId(),
                    'id_user' => $item->getIdUser()->getId(),
                    'libelle' => $item->getLibelle(),
                    //'imageurl' => $item->getImageurl(),
                    'archived' => $item->isArchived(),
                    ];
            }, $echangeItems);

            return [
                'id' => $echange->getId(),
                'titre_echange' => $echange->getTitreEchange(),
                'user1' => $echange->getIdUser1()->getId(),
                'user2' => $echange->getIdUser2()->getId(),
                'date_echange' => $echange->getDateEchange(),
                'archived' => $echange->isArchived(),
                'echange_items' => $itemsArray,
                'etat_livraison' => $echange->getLivEtat(),
            ];
        }, $list);
        // create a JSON response containing the items array
        return new JsonResponse(['echanges' => $echangesArray]);
    }

    #[Route('/echange/mobile/listLivreur', name: 'app_echangeListLivreur_m', methods: ['GET', 'POST'])]
    public function mobileEchangeLivreur(EchangeRepository $repository, ItemRepository $itemsRep): JsonResponse
    {
        $qb = $repository->createQueryBuilder('e')
            ->where('e.archived = :archived')
            ->andWhere('e.id_user1 IS NOT NULL')
            ->andWhere('e.id_user2 IS NOT NULL')
            ->andWhere('e.liv_etat = :liv_etat')
            ->setParameter('archived', false)
            ->setParameter('liv_etat', 'Non_Accepter');
        $list = $qb->getQuery()->getResult();


        $echangesArray = array_map(function (Echange $echange) use ($itemsRep) {
            $echangeItems = $itemsRep->findBy(['id_echange' => $echange->getId(), 'archived' => false]);

            $itemsArray = array_map(function (Item $item) {
                return [
                    'id' => $item->getId(),
                    'id_echange' => $item->getIdEchange()->getId(),
                    'id_user' => $item->getIdUser()->getId(),
                    'libelle' => $item->getLibelle(),
                    //'imageurl' => $item->getImageurl(),
                    'archived' => $item->isArchived(),
                ];
            }, $echangeItems);

            return [
                'id' => $echange->getId(),
                'titre_echange' => $echange->getTitreEchange(),
                'user1' => $echange->getIdUser1()->getId(),
                'user2' => $echange->getIdUser2()->getId(),
                'date_echange' => $echange->getDateEchange(),
                'archived' => $echange->isArchived(),
                'echange_items' => $itemsArray,
                'etat_livraison' => $echange->getLivEtat(),
            ];
        }, $list);
        // create a JSON response containing the items array
        return new JsonResponse(['echanges' => $echangesArray]);
    }

}
