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

}
