<?php

namespace App\Controller;

use App\Entity\CategorieItems;
use App\Repository\CategorieItemsRepository;
use App\Form\CategorieItemsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class CategorieItemsController extends AbstractController
{
    #[Route('/categorie/items', name: 'app_categorie_items')]
    public function index(): Response
    {
        return $this->render('categorie_items/index.html.twig', [
            'controller_name' => 'CategorieItemsController',
        ]);
    }

    #[Route('/categorieitems/back/list', name: 'app_categorieItemsList_b')]
    public function listB(Request $request, CategorieItemsRepository $repository, ManagerRegistry $doctrine): Response
    {
    
        $list = $repository->findUnarchived();
        $em = $doctrine->getManager();
        $categorieitems = new categorieitems();
        $form = $this->createForm(CategorieItemsType::class, $categorieitems);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieitems->setArchived(0);
            $em->persist($categorieitems);
            $em->flush();
            return $this->redirectToRoute('app_categorieItemsList_b');
        }

        return $this->renderForm('item/back/categorieitems.html.twig', [
            'controller_name' => 'List des Categoires Items',
            'list' => $list,
            'formA' => $form
        ]);
    }


    #[Route('/back/categorieitems/remove/{id}', name: 'app_categorieItemsRemove_b')]
    public function removeB(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(categorieitems::class);
        $em = $doctrine->getManager();
        $item = $repository->find($id);
        $item->setArchived(1) ;
        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('app_categorieItemsList_b');
    }


    
    #[Route('/back/categorieitems/modify/{id}', name: 'app_categorieItemsModify_b')]
    public function modifyB(Request $request, ManagerRegistry $doctrine, $id): Response
    {

        $nom = $request->get('x');
        $repository = $doctrine->getRepository(categorieitems::class);
        $em = $doctrine->getManager();
        $categorieitems = $repository->find($id);
        $categorieitems->setNomCategorie($nom);
        $em->persist($categorieitems);
        $em->flush();
            return $this->redirectToRoute('app_categorieItemsList_b');

    }


}
