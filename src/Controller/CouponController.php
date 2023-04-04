<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Form\CouponType;
use App\Repository\CouponRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CouponController extends AbstractController
{
    #[Route('/coupon', name: 'app_coupon')]
    public function index(CouponRepository $couponRepository): Response
    {
        return $this->render('coupon/index.html.twig', [
            //find articles that are not archived
            'coupons' => $couponRepository->findBy(['archived' => false]),
        ]);
    }

    #[Route('/coupon/afficher', name: 'app_coupon_afficher')]
    public function read(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Coupon::class);
        $list = $repository->findAll();
        return $this->render('coupon/index.html.twig', [
            'coupons' => $list,
        ]);
    }
    #[Route('/coupon/ajouter', name: 'app_coupon_ajouter')]
    public function ajouter(
        ManagerRegistry $doctrine,
        Request $request,
        CouponRepository $couponRepository,
    ): Response {
        $coupon = new Coupon();
        $form = $this->createForm(CouponType ::class, $coupon);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($coupon);
            $em->flush();
            return $this->redirectToRoute('app_coupon_afficher');
        }
        return $this->renderForm('coupon/ajouter.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/coupon/delete/{id}', name: 'app_coupon_delete')]
    public function delete(
        CouponRepository $repository,
                         $id,
        ManagerRegistry $doctrine
    ): Response {
        $em = $doctrine->getManager();
        $coupon = $repository->find($id);
        $em->remove($coupon);
        $em->flush();
        return $this->redirectToRoute('app_coupon_afficher');
    }

    #[Route('/edit/{id}', name: 'app_coupon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coupon $coupon, Coupon $couponRepository): Response
    {
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $couponRepository->save($coupon, true);

            return $this->redirectToRoute('app_coupon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coupon/edit.html.twig', [
            'coupon' => $coupon,
            'form' => $form,
        ]);
    }


}

