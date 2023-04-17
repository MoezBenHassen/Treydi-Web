<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\LikeItems;
use App\Entity\Utilisateur;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Repository\LikeItemsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class ItemController extends AbstractController
{
    #[Route('/item', name: 'app_item')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }


    #[Route('/item/front/list', name: 'app_itemList_f')]
    public function listF(ItemRepository $repository): Response
    {

        $list = $repository->findUnarchivedFront(1);

        return $this->render('item/front/index.html.twig', [
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
        $item->setArchived(1);
        $em->flush();

        return $this->redirectToRoute('app_itemList_f');
    }

    #[Route('/back/item/remove/{id}', name: 'app_itemRemove_b')]
    public function removeB(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $item->setArchived(1);
        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('app_itemList_b');
    }

    #[Route('item/front/add', name: 'app_itemAdd_f')]
    public function addF(Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Utilisateur::class);
        $em = $doctrine->getManager();
        $item = new item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageurl')->getData();
            if ($file) {
                $base64 = base64_encode(file_get_contents($file->getPathname()));
                $base64 = "data:image/jpeg;base64, " . $base64;
                $item->setImageurl($base64);
            }
            if ($item->getType() == "Virtuelle" || $item->getType() == "Service") {
                $item->setEtat("Nul");
            }
            $user = $repository->find(1);
            $item->setIdUser($user);
            $item->setArchived(0);
            $em->persist($item);
            $em->flush();
            return $this->redirectToRoute('app_itemList_f');
        }
        return $this->renderForm('item/front/add.html.twig', array('formA' => $form));
    }

    #[Route('/item/back/add', name: 'app_itemAdd_b')]
    public function addB(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $repository = $doctrine->getRepository(Utilisateur::class);
        $em = $doctrine->getManager();
        $item = new item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);



        $filename = "";
        $file = $form['imageurl']->getData();
        if ($file) {
            $filename = $file->getClientOriginalName();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repository->find(1);
            $file = $form->get('imageurl')->getData();
            if ($file) {

                $base64 = base64_encode(file_get_contents($file->getPathname()));
                $base64 = "data:image/jpeg;base64, " . $base64;
                $item->setImageurl($base64);
            }
            if ($item->getType() == "Virtuelle" || $item->getType() == "Service") {
                $item->setEtat("Nul");
            }

            if (strpos($filename, '.jpg') !== false) {
                $item->setIdUser($user);
                $item->setArchived(0);
                $em->persist($item);
                $em->flush();
                return $this->redirectToRoute('app_itemList_b');
            } else {
                return $this->renderForm('item/back/add.html.twig', array('formA' => $form, 'err' => '  ●             Fichier doit etre png ou jpg'));
            }
        } else {
            if (strpos($filename, '.jpg') !== true) {
                return $this->renderForm('item/back/add.html.twig', array('formA' => $form, 'err' => '  ●             Fichier doit etre png ou jpg'));
            }
        }





        return $this->renderForm('item/back/add.html.twig', array('formA' => $form, 'err' => ''));
    }

    #[Route('/item/front/modify/{id}', name: 'app_itemModify_f')]
    public function modifyF(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageurl')->getData();
            if ($file) {
                $base64 = base64_encode(file_get_contents($file->getPathname()));
                $base64 = "data:image/jpeg;base64, " . $base64;
                $item->setImageurl($base64);
            }
            if ($item->getType() == "Virtuelle" || $item->getType() == "Service") {
                $item->setEtat("Nul");
            }
            $em->flush();
            return $this->redirectToRoute('app_itemList_f');
        }
        return $this->renderForm('item/front/modify.html.twig', array('formA' => $form));
    }


    #[Route('/item/back/modify/{id}', name: 'app_itemModify_b')]
    public function modifyB(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageurl')->getData();
            if ($file) {
                $base64 = base64_encode(file_get_contents($file->getPathname()));
                $base64 = "data:image/jpeg;base64, " . $base64;
                $item->setImageurl($base64);
            }
            if ($item->getType() == "Virtuelle" || $item->getType() == "Service") {
                $item->setEtat("Nul");
            }
            $em->flush();
            return $this->redirectToRoute('app_itemList_b');
        }
        return $this->renderForm('item/back/modify.html.twig', array('formA' => $form));
    }



    #[Route('/item/front/like/{id}', name: 'app_itemLike_f')]
    public function like(Request $request, ManagerRegistry $doctrine, $id,LikeItemsRepository $repository): Response
    {
        $repositoryitem = $doctrine->getRepository(item::class);
        $repositorylike = $doctrine->getRepository(likeitems::class);
        $em = $doctrine->getManager();
        $item = $repositoryitem->find($id);
        $like = $repository->obtain($id,1);

        if (!$like) {
            $item->setLikes($item->getLikes() + 1);
            $likex = new likeitems();
            $likex->setiduser(1);
            $likex->setiditem($id);
            $likex->setlike(0);
            $em->persist($likex);
            $em->persist($item);
            $em->flush();

        } else {
            if ($like[0]->getlike() == 0) {
                $em->remove($like[0]);
                $item->setLikes($item->getLikes() - 1);
                $em->persist($item);
                $em->flush();
            } else {
                $item->setDislikes($item->getDislikes() - 1);
                $item->setLikes($item->getLikes() + 1);
                $like[0]->setlike(0);
                $em->persist($like[0]);
                $em->persist($item);
                $em->flush();
            }

        }
        return $this->redirectToRoute('app_itemList_f');
    }

    #[Route('/item/front/dislike/{id}', name: 'app_itemDislike_f')]
    public function dislike(Request $request, ManagerRegistry $doctrine, $id,LikeItemsRepository $repository): Response
    {
        $repositoryitem = $doctrine->getRepository(item::class);
        $repositorylike = $doctrine->getRepository(likeitems::class);
        $em = $doctrine->getManager();
        $item = $repositoryitem->find($id);
        $like = $repository->obtain($id,1);

        if (!$like) {
            $item->setDislikes($item->getDislikes() + 1);
            $likex = new likeitems();
            $likex->setiduser(1);
            $likex->setiditem($id);
            $likex->setlike(1);
            $em->persist($likex);
            $em->persist($item);
            $em->flush();

        } else {
            if ($like[0]->getlike() == 1) {
                $em->remove($like[0]);
                $item->setDislikes($item->getDislikes() - 1);
                $em->persist($item);
                $em->flush();
            } else {
                $item->setDislikes($item->getDislikes() + 1);
                $item->setLikes($item->getLikes() - 1);
                $like[0]->setlike(1);
                $em->persist($like[0]);
                $em->persist($item);
                $em->flush();
            }

        }
        return $this->redirectToRoute('app_itemList_f');
    }
}
