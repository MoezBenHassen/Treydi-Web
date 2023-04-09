<?php

namespace App\Controller;

use App\Entity\CategorieCoupon;
use App\Form\CategorieCouponType;
use App\Repository\CategorieCouponRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieCouponController extends AbstractController
{
    #[Route('/categorie/coupon', name: 'app_categorie_coupon')]
    public function index(): Response
    {
        return $this->render('categorie_coupon/index.html.twig', [
            'controller_name' => 'CategorieCouponController',
        ]);
    }

    #[Route('/categoriecoupon/show', name: 'app_categoriecoupon_show')]
    public function show(\Doctrine\Persistence\ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(CategorieCoupon::class);
        $list = $repository->findAll();
        return $this->render('categorie_coupon/show.html.twig', [
            'categorie_coupons' => $list,
        ]);
    }
    #[Route('/categoriecoupon/add', name: 'app_categoriecoupon_ajouter')]
    public function ajouter(
        \Doctrine\Persistence\ManagerRegistry $doctrine,
        Request $request,
        CategorieCouponRepository $categorieCouponRepository,
    ): Response {
        $categorie_coupon = new CategorieCoupon();
        $form = $this->createForm(CategorieCouponType ::class, $categorie_coupon);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($categorie_coupon);
            $em->flush();
            return $this->redirectToRoute('app_categoriecoupon_show');
        }
        return $this->renderForm('categorie_coupon/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/categorie_coupon/delete/{id}', name: 'app_categoriecoupon_delete', methods: ['POST','GET'])]
    public function deleteReclamation(CategorieCoupon $categorieCoupon, \Doctrine\Persistence\ManagerRegistry $doctrine): Response
    {
        $categorieCoupon->setArchived(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('app_categoriecoupon_show');
    }

    #[Route('/edit/{id}', name: 'app_categoriecoupon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieCoupon $categorieCoupon, CategorieCouponRepository $categorieCouponRepository): Response
    {
        $form = $this->createForm(CategorieCouponType::class, $categorieCoupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieCouponRepository->save($categorieCoupon, true);

            return $this->redirectToRoute('app_coupon_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_coupon/edit.html.twig', [
            'coupon' => $categorieCoupon,
            'form' => $form,
        ]);
    }

}
