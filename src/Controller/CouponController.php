<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Utilisateur;
use App\Form\CouponType;
use App\Form\EditCouponType;
use App\Form\SearchForm;
use App\Form\SearchFormType;
use App\Repository\CouponRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;



class CouponController extends AbstractController
{
    #[Route('/coupon', name: 'app_coupon')]
    public function index(CouponRepository $couponRepository, Request $request): Response
    {
        $form = $this->createForm(CouponType::class);
        $form->handleRequest($request);
        $search = null;
        $date_expiration = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->get('titreCoupon')->getData();
            $description = $form->get('descriptionCoupon')->getData();
            $date_expiration = $form->get('dateExpiration')->getData();
            $archived = $form->get('archived')->getData();
            $idCategorie = $form->get('idCategorie')->getData();
            $etat_coupon = $form->get('etat_coupon')->getData();

            $queryList = $couponRepository->findByAllAttributes($search, $date_expiration, $description, $archived, $idCategorie, $etat_coupon);
            $couponslist = $queryList;
        } else {
            $queryList = $couponRepository->findByArchived(false);
            $couponslist = $queryList;
        }

        return $this->render('article/index.html.twig', [
            'form'=> $form->createView(),
            'coupons' => $couponslist,
            'search' => $search,
            'date_expiration' => $date_expiration,
        ]);
    }



    #[Route('/scoreboard', name: 'app_coupon_scoreboard')]
    public function scoreboard(ManagerRegistry $doctrine): Response
    {
        $userRepository = $doctrine->getRepository(Utilisateur::class);
        $users = $userRepository->findBy([], ['score' => 'DESC']);

        return $this->render('coupon/scoreboard.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/coupon/show', name: 'app_coupon_show')]
    public function show(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Coupon::class);
        $list = $repository->findAll();
        return $this->render('coupon/show.html.twig', [
            'coupons' => $list,
        ]);
    }





    #[Route('/coupon/add', name: 'app_coupon_ajouter')]
    public function ajouter(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $coupon = new Coupon();
        $coupon->setArchived(0); // set archived property to 0 (not archived)
        $coupon->setEtatCoupon("VALID");
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($coupon);
            $em->flush();
            return $this->redirectToRoute('app_coupon_show');
        }

        return $this->renderForm('coupon/add.html.twig', array('form' => $form));
    }


    #[Route('/coupon/delete/{id}', name: 'app_coupon_delete', methods: ['POST', 'GET'])]
    public function deleteReclamation(Coupon $coupon, ManagerRegistry $doctrine): Response
    {
        $coupon->setArchived(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('app_coupon_show');
    }

    #[Route('coupon/edit/{id}', name: 'app_coupon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coupon $coupon, CouponRepository $couponRepository): Response
    {
        $form = $this->createForm(EditCouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $couponRepository->save($coupon, true);

            return $this->redirectToRoute('app_coupon_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('coupon/edit.html.twig', [
            'coupon' => $coupon,
            'form' => $form,
        ]);
    }





}

