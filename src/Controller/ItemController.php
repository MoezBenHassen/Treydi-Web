<?php

namespace App\Controller;

use App\Entity\CategorieItems;
use App\Entity\CommentItems;
use App\Entity\Item;
use App\Entity\LikeItems;
use App\Entity\ViewItems;
use App\Entity\Utilisateur;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Repository\LikeItemsRepository;
use App\Repository\ViewItemsRepository;
use App\Repository\CommentItemsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Dompdf\Options;
use Dompdf\Dompdf;


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
    public function listF(ItemRepository $repository,ManagerRegistry $doctrine): Response
    {
        $rrepository = $doctrine->getRepository(CategorieItems::class); 
        $listc = $rrepository->findBy(['archived' => 0]);
        $list = $repository->findUnarchivedFront($this->getUser());

        return $this->render('item/front/index.html.twig', [
            'controller_name' => 'List des Items',
            'list' => $list,
            'listc' => $listc
        ]);
    }

    #[Route('/item/front/listall', name: 'app_itemListall_f')]
    public function listallF(ManagerRegistry $doctrine, ItemRepository $repository): Response
    {

        $list = $repository->findAlll();
        $rrepository = $doctrine->getRepository(CategorieItems::class);
        $listc = $rrepository->findBy(['archived' => 0]);

        return $this->render('item/front/all.html.twig', [
            'controller_name' => 'List des Items',
            'list' => $list,
            'listc' => $listc,
            'cat' => ""

        ]);
    }


    #[Route('/item/front/listall/filter/', name: 'app_itemFilter_f')]
    public function filterF(Request $request, ManagerRegistry $doctrine, ItemRepository $repository): Response
    {

        $lib = $request->get('libelle');
        $c1 = $request->get('physiquen');
        $c2 = $request->get('physiqueo');
        $c3 = $request->get('virtuelle');
        $c4 = $request->get('service');

        $list = $repository->findAlll();
        $rrepository = $doctrine->getRepository(CategorieItems::class);
        $listc = $rrepository->findBy(['archived' => 0]);



        $filteredItems = array_filter($list, function ($item) use ($c1, $c2, $c3, $c4) {
            $included = true;
            if ($c1 && $item->getType() !== 'Physique' && $item->getEtat() !== 'Neuf') {
                $included = false;
                $this->addFlash('success', 'Your operation was successful!');
            }
            if ($c2 && $item->getType() !== 'Physique' && $item->getEtat() !== 'Occasion') {
                $included = false;
            }
            if ($c3 && $item->getType() !== 'Virtuelle') {
                $included = false;
            }
            if ($c4 && $item->getType() !== 'Service') {
                $included = false;
            }
            return $included;
        });

        $filteredItems = array_filter($filteredItems, function ($item) use ($lib) {
            $words = explode(' ', $lib);
            foreach ($words as $word) {
                if (stripos($item->getLibelle(), $word) !== false) {
                    return true;
                }
            }
            return false;
        });


        return $this->render('item/front/all.html.twig', [
            'controller_name' => 'List des Items',
            'list' => $filteredItems,
            'listc' => $listc,
            'cat' => " | Filtree"

        ]);
    }

    #[Route('/item/front/listallc/{id}', name: 'app_itemListallc_f')]
    public function listallcF(ManagerRegistry $doctrine, ItemRepository $repository, $id): Response
    {

        $list = $repository->findBy(['id_categorie' => $id, 'archived' => 0]);
        $rrepository = $doctrine->getRepository(CategorieItems::class);
        $listc = $rrepository->findBy(['archived' => 0]);
        $a = $rrepository->find($id);


        return $this->render('item/front/all.html.twig', [
            'controller_name' => 'List des Items',
            'list' => $list,
            'listc' => $listc,
            'cat' => "| " . $a->getNomCategorie()
        ]);
    }

    #[Route('/item/back/list', name: 'app_itemList_b')]
    public function listB(ManagerRegistry $doctrine, ItemRepository $repository): Response
    {

        $list = $repository->findAlll();
        $rrepository = $doctrine->getRepository(CategorieItems::class);
        $listc = $rrepository->findAll();

        return $this->render('item/back/index.html.twig', [
            'controller_name' => 'List des Items',
            'list' => $list,
            'listc' => $listc
        ]);
    }

    #[Route('/item/back/stats', name: 'app_itemStats_b')]
    public function statsB(ManagerRegistry $doctrine, ItemRepository $repository): Response
    {

        $list = $repository->findAll();
        $rrepository = $doctrine->getRepository(CategorieItems::class);
        $listc = $rrepository->findAll();

        return $this->render('item/back/stat.html.twig', [
            'controller_name' => 'Stats des Items',
            'list' => $list,
            'listc' => $listc
        ]);
    }


    #[Route('/front/item/remove/{id}', name: 'app_itemRemove_f')]
    public function removeF(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $rrepository = $doctrine->getRepository(CategorieItems::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $cat = $rrepository->find($item->getIdcategorie());
        $cat->setQt($cat->getqt() - 1);
        $item->setArchived(1);
        $em->persist($cat);
        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('app_itemList_f');
    }

    #[Route('/back/item/remove/{id}', name: 'app_itemRemove_b')]
    public function removeB(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(item::class);
        $rrepository = $doctrine->getRepository(CategorieItems::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $item->setArchived(1);
        $cat = $rrepository->find($item->getIdcategorie());
        $cat->setQt($cat->getqt() - 1);
        $em->persist($cat);
        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('app_itemList_b');
    }

    #[Route('item/front/add', name: 'app_itemAdd_f')]
    public function addF(Security $security,Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Utilisateur::class);
        $em = $doctrine->getManager();
        $item = new item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        $rrepository = $doctrine->getRepository(CategorieItems::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $cat = $rrepository->find($form->get('id_categorie')->getData());
            $cat->setQt($cat->getqt() + 1);

            $file = $form->get('imageurl')->getData();
            if ($file) {
                $base64 = base64_encode(file_get_contents($file->getPathname()));
                $base64 = "data:image/jpeg;base64, " . $base64;
                $item->setImageurl($base64);
            }
            if ($item->getType() == "Virtuelle" || $item->getType() == "Service") {
                $item->setEtat("Nul");
            }
            $user = $repository->find($security->getUser()->getId());
            $item->setIdUser($user);
            $item->setArchived(0);
            $em->persist($cat);
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

        $rrepository = $doctrine->getRepository(CategorieItems::class);

        $filename = "";
        $file = $form['imageurl']->getData();
        if ($file) {
            $filename = $file->getClientOriginalName();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repository->find($this->getUser()->getId());
            $cat = $rrepository->find($form->get('id_categorie')->getData());
            $cat->setQt($cat->getqt() + 1);
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
                $em->persist($cat);
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
    public function like(Request $request, ManagerRegistry $doctrine, $id, LikeItemsRepository $repository): Response
    {
        $repositoryitem = $doctrine->getRepository(item::class);
        $repositorylike = $doctrine->getRepository(likeitems::class);
        $em = $doctrine->getManager();
        $item = $repositoryitem->find($id);
        $like = $repository->obtain($id, 1);

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
        return $this->redirectToRoute('app_itemListall_f');
    }

    #[Route('/item/front/dislike/{id}', name: 'app_itemDislike_f')]
    public function dislike(Request $request, ManagerRegistry $doctrine, $id, LikeItemsRepository $repository): Response
    {
        $repositoryitem = $doctrine->getRepository(item::class);
        $repositorylike = $doctrine->getRepository(likeitems::class);
        $em = $doctrine->getManager();
        $item = $repositoryitem->find($id);
        $like = $repository->obtain($id, 1);

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
        return $this->redirectToRoute('app_itemListall_f');
    }

    #[Route('/item/front/view/{id}', name: 'app_itemViews_f')]
    public function view(ManagerRegistry $doctrine, $id, ViewItemsRepository $repository)
    {
        $repositoryitem = $doctrine->getRepository(item::class);
        $repositoryview = $doctrine->getRepository(viewitems::class);
        $em = $doctrine->getManager();
        $item = $repositoryitem->find($id);
        $view = $repository->obtain($id, 1);

        if (!$view) {
            $item->setViews($item->getViews() + 1);
            $viewx = new viewitems();
            $viewx->setiduser(1);
            $viewx->setiditem($id);
            $em->persist($viewx);
            $em->flush();
        }
    }


    #[Route('/item/front/detail/{id}', name: 'app_itemDetail_f')]
    public function detailF(ManagerRegistry $doctrine, CommentItemsRepository $rrrepository, ViewItemsRepository $rrepository, ItemRepository $repository, $id): Response
    {
        $this->view($doctrine, $id, $rrepository);
        $item = $repository->find($id);
        $comment = $rrrepository->findBy(['itemid' => $id]);

        $items = $repository->findAlll();
        $searchTerm = $item->getLibelle();
        $itemsc = array_filter($items, function ($item) use ($searchTerm) {
            $words = explode(' ', $searchTerm);
            foreach ($words as $word) {
                if (stripos($item->getLibelle(), $word) !== false) {
                    return true;
                }
            }
            return false;
        });

        $itemsd = $repository->findAlll();
        $et = $item->getEtat();

        if ($et == "Neuf") {
            $itemsd = array_filter($items, function ($item) use ($et) {
                return $item->getEtat() == "Occasion";
            });
        } else {
            $itemsd = array_filter($items, function ($item) use ($et) {
                return $item->getEtat() == "Neuf";
            });
        }

        $searchTerm = $item->getLibelle();
        $itemsd = array_filter($itemsd, function ($item) use ($searchTerm) {
            $words = explode(' ', $searchTerm);
            foreach ($words as $word) {
                if (stripos($item->getLibelle(), $word) !== false) {
                    return true;
                }
            }
            return false;
        });


        $itemsn = $repository->findAlll();
        $searchValue = $item->getIdCategorie();
        $itemsn = array_filter($items, function ($item) use ($searchValue) {
            return $item->getIdCategorie() == $searchValue;
        });

        shuffle($itemsc);
        shuffle($itemsd);
        shuffle($itemsn);

        return $this->render('item/front/details.html.twig', [
            'controller_name' => 'List des Items',
            'item' => $item,
            'comment' => $comment,
            'itemsc' => $itemsc,
            'itemsn' => $itemsn,
            'itemsd' => $itemsd
        ]);
    }


    #[Route('/front/item/comment/{id}', name: 'app_commentItem_f')]
    public function comment(Request $request, ManagerRegistry $doctrine, $id): Response
    {

        $nom = $request->get('x');
        $repository = $doctrine->getRepository(CommentItems::class);
        $rrepository = $doctrine->getRepository(Utilisateur::class);
        $em = $doctrine->getManager();
        $comment = new CommentItems();
        $user = $rrepository->find($this->getUser()->getId());
        $comment->setUserid($user);
        $comment->setItemid($id);
        $comment->setComment($nom);
        $em->persist($comment);
        $em->flush();
        return $this->redirectToRoute('app_itemDetail_f', ['id' => $id]);
    }

    #[Route('/front/item/commentdel/{id}_{idu}', name: 'app_itemCommentDel_f')]
    public function commentdel(Request $request, ManagerRegistry $doctrine, $id, $idu): Response
    {
        $repository = $doctrine->getRepository(CommentItems::class);
        $em = $doctrine->getManager();
        $comment =  $repository->find($id);
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('app_itemDetail_f', ['id' => $idu]);
    }

    #[Route('/front/item/pdf/', name: 'app_itemPdf_f')]
    public function generatePdf(ItemRepository $repository): Response
    {
        // Get all items from the database using the Item class
        $items = $repository->findAlll();

        // Set the PDF options
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);

        // Instantiate Dompdf with the options
        $dompdf = new Dompdf($options);

        // Generate the HTML for the PDF
        $html = $this->renderView('item/front/pdf.html.twig', ['items' => $items]);

        // Load the HTML into Dompdf
        $dompdf->loadHtml($html);

        // Set the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF to the browser
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="items.pdf"',
        ]);
    }
}
