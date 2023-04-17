<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Utilisateur;
use App\Form\CouponType;
use App\Form\EditCouponType;
use App\Form\SearchForm;
use App\Repository\CouponRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CouponController extends AbstractController
{
    #[Route('/coupon', name: 'app_coupon')]
    public function index(CouponRepository $couponRepository): Response
    {
        return $this->render('coupon/index.html.twig', [
            'coupons' => $couponRepository->findAll()
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

    /*
    #[Route('/coupon/add', name: 'app_coupon_ajouter')]
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
            return $this->redirectToRoute('app_coupon_show');
        }
        return $this->renderForm('coupon/add.html.twig', [
            'form' => $form,
        ]);  */

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

    #[Route('/couponsearch', name: 'app_couponcontroller_search')]
    public function search(Request $request)
    {
        $form = $this->createForm(SearchForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $searchTerm = $form->get('search')->getData();
            $archived = $form->get('archived')->getData();
            $isValid = $form->get('isValid')->getData();
            $categorie = $form->get('categorie')->getData();

            $qb = $this->getDoctrine()->getRepository(Coupon::class)->createQueryBuilder('c');

            if ($searchTerm) {
                $qb->andWhere('c.titre_coupon LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $searchTerm . '%');
            }

            if ($archived !== null) {
                $qb->andWhere('c.archived = :archived')
                    ->setParameter('archived', $archived);
            }

            if ($categorie !== null) {
                $qb->andWhere('c.id_categorie = :categorie')
                    ->setParameter('categorie', $categorie);
            }

            if ($isValid !== null) {
                $qb->andWhere('c.isValid = :isValid')
                    ->setParameter('isValid', $isValid);
            }

            $coupons = $qb->getQuery()->getResult();
            return $this->render('coupon/show.html.twig', [
                'form' => $form->createView(),
                'coupons' => $coupons
            ]);

        }


    }

}

