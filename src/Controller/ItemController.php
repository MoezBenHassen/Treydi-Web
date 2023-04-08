<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Utilisateur;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }


    #[Route('/front/item/list', name: 'app_itemList_f')]
    public function listF(ItemRepository $repository): Response
    {
    
        $list = $repository->findUnarchivedFront(1);

        return $this->render('front/item/list.html.twig', [
            'controller_name' => 'List des Items',
            'list' => $list
        ]);
    }

    #[Route('/item/back/list', name: 'app_itemList_b')]
    public function listB(ItemRepository $repository): Response
    {
    
        $list = $repository->findUnarchived();

        return $this->render('item/back/index.html.twig', [
            'controller_name' => 'List des Items',
            'list' => $list
        ]);
    }


    #[Route('/front/item/remove/{id}', name: 'app_itemRemove_f')]
    public function removeF(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $item->setArchived(1) ;
        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('app_itemList_f');
    }

    #[Route('/back/item/remove/{id}', name: 'app_itemRemove_b')]
    public function removeB(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $item->setArchived(1) ;
        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('app_itemList_b');
    }
    
    #[Route('front/item/add', name: 'app_itemAdd_f')]
    public function addF(Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Utilisateur::class);
        $em = $doctrine->getManager();
        $item = new item();
        $form = $this->createForm(ItemType::class, $item);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $user = $repository->find(1);
            $item->setIdUser($user);
            $item->setArchived(0);
            $em->persist($item);
            $em->flush();
            return $this->redirectToRoute('app_itemList_f');
        }
        return $this->renderForm('front/item/add.html.twig', array('formA' => $form));
    }

    #[Route('/item/back/add', name: 'app_itemAdd_b')]
    public function addB(Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Utilisateur::class);
        $em = $doctrine->getManager();
        $item = new item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $user = $repository->find(1);
            $item->setIdUser($user);
            $item->setArchived(0);
            $em->persist($item);
            $em->flush();
            return $this->redirectToRoute('app_itemList_b');
        }
        return $this->renderForm('item/back/add.html.twig', array('formA' => $form));
    }

    #[Route('/front/item/modify/{id}', name: 'app_itemModify_f')]
    public function modifyF(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $form = $this->createForm(ItemType::class, $item);
        $form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $em->flush();
            return $this->redirectToRoute('app_itemList_f');
        }
        return $this->renderForm('front/item/modify.html.twig', array('formA' => $form));
    }

    
    #[Route('/back/item/modify/{id}', name: 'app_itemModify_b')]
    public function modifyB(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $form = $this->createForm(ItemType::class, $item);
        $form->add('Modifier', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $em->flush();
            return $this->redirectToRoute('app_itemList_b');
        }
        return $this->renderForm('back/item/modify.html.twig', array('formA' => $form));
    }


}
