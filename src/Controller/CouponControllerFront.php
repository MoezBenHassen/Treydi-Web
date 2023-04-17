<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Utilisateur;
use App\Repository\CouponRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;

class CouponControllerFront extends AbstractController
{
    #[Route('/coupon', name: 'app_coupon')]
    public function index(CouponRepository $couponRepository): Response
    {
        return $this->render('coupon/index.html.twig', [
            'coupons' => $couponRepository->findAll()
        ]);
    }

    #[Route('/frontscoreboard', name: 'app_coupon_frontscoreboard')]
    public function scoreboard(ManagerRegistry $doctrine): Response
    {
        $userRepository = $doctrine->getRepository(Utilisateur::class);
        $users = $userRepository->findBy([], ['score' => 'DESC']);

        return $this->render('coupon/scoreboardfront.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/mycoupons', name: 'app_coupon_peruser')]
    public function usercoupons(ManagerRegistry $doctrine, Security $security): Response
    {
        $user = $security->getUser();
        $userRepository = $doctrine->getRepository(Utilisateur::class);

        if (!$user) {
            throw new \Exception('User not logged in');
        }

        $userId = $user->getId();
        $couponRepository = $doctrine->getRepository(Coupon::class);
        $coupons = $couponRepository->findBy(['id' => $userId]);

        return $this->render('coupon/mycoupons.html.twig', [
            'coupons' => $coupons,
        ]);
    }

    #[Route('/coupon/transform', name: 'app_coupon_transform')]
    public function transformercoupons(ManagerRegistry $doctrine, Request $request): Response
    {
        $couponMensuelQty = $request->request->get('couponMensuelQty');
        $couponSpecialQty = $request->request->get('couponSpecialQty');
        $couponExclusifQty = $request->request->get('couponExclusifQty');

        for ($i = 0; $i < $couponMensuelQty; $i++) {
            $couponMensuel = new Coupon();
            $couponMensuel->setType('Mensuel');
            // set any other required properties for the coupon
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($couponMensuel);
            $entityManager->flush();
        }

        for ($i = 0; $i < $couponSpecialQty; $i++) {
            $couponSpecial = new Coupon();
            $couponSpecial->setType('Special');
            // set any other required properties for the coupon
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($couponSpecial);
            $entityManager->flush();
        }

        for ($i = 0; $i < $couponExclusifQty; $i++) {
            $couponExclusif = new Coupon();
            $couponExclusif->setType('Exclusif');
            // set any other required properties for the coupon
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($couponExclusif);
            $entityManager->flush();
        }

        return $this->render('coupon/transformscore.html.twig', [
            'couponMensuelQty' => $couponMensuelQty,
            'couponSpecialQty' => $couponSpecialQty,
            'couponExclusifQty' => $couponExclusifQty,
        ]);
    }


    #[Route('/qrcode', name: 'app_coupon_qrcode')]
    public function qrcode(Coupon $coupon): Response
    {
        $qrCode = new QrCode($coupon->getCode());

        return $this->render('coupon/qr_code.html.twig', [
            'qrCode' => $qrCode->writeString(),
        ]);
    }



}
