<?php

namespace App\Controller;

use App\Entity\CategorieCoupon;
use App\Entity\Coupon;
use App\Entity\Utilisateur;
use App\Repository\CouponRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;


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
        $coupons = $couponRepository->findBy(['id_user' => $userId]);

        return $this->render('coupon/mycoupons.html.twig', [
            'coupons' => $coupons,
        ]);
    }

    #[Route('/transform', name: 'App_Transform')]
    public function Transform(ManagerRegistry $doctrine, Request $request, Security $security): Response
    {
        return $this->render('coupon/transformscore.html.twig');
    }

    #[Route('/transform1', name: 'AffecterCouponMensuel')]
    public function AffecterCouponMensuel(ManagerRegistry $doctrine, Request $request, Security $security): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $categorieRepository = $entityManager->getRepository(categorieCoupon::class);
        $categorie = $categorieRepository->find(1); // Assumption: category with ID 1 exists
        $lastCoupon = $entityManager->getRepository(Coupon::class)->findOneBy(['id_categorie' => 1], ['id' => 'DESC']);
        $lastCode = $lastCoupon ? $lastCoupon->getCode() : 0;
        $newCode = intval($lastCode) . "CasMaiCoupon" . "1";
        $couponMensuel = new Coupon();
        $couponMensuel->setIdCategorie($categorie);
        $couponMensuel->setTitreCoupon('Coupon Mai Mensuel');
        $couponMensuel->setDescriptionCoupon('50% off Delivery');
        $couponMensuel->setDateExpiration(new \DateTime('2021-05-31'));
        $couponMensuel->setEtatCoupon("VALID");
        $couponMensuel->setCode($newCode);
        $user = $security->getUser();
        $couponMensuel->setIdUser($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($couponMensuel);
        $entityManager->flush();
        $this->qrcode($newCode);
        return $this->render('coupon/qr_code.html.twig', [
            'qrCodeDataUri' => $newCode,
            
        ]);
    }


    #[Route('/transform2', name: 'AffecterCouponSpecial')]
    public function AffecterCouponSpecial(ManagerRegistry $doctrine, Request $request, Security $security): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorieRepository = $entityManager->getRepository(categorieCoupon::class);
        $categorie = $categorieRepository->find(2); // Assumption: category with ID 1 exists
        $lastCoupon = $entityManager->getRepository(Coupon::class)->findOneBy(['id_categorie' => 2], ['id' => 'DESC']);
        $lastCode = $lastCoupon ? $lastCoupon->getCode() : 0;
        $newCode = intval($lastCode) . "SpecMaiCoupon" . "1";
        $couponSpecial = new Coupon();
        $couponSpecial->setIdCategorie($categorie);
        $couponSpecial->setTitreCoupon('Coupon Mai Special');
        $couponSpecial->setDescriptionCoupon('100% off Delivery');
        $couponSpecial->setDateExpiration(new \DateTime('2021-05-31'));
        $couponSpecial->setEtatCoupon("VALID");
        $couponSpecial->setCode($newCode);
        $user = $security->getUser();
        $couponSpecial->setIdUser($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($couponSpecial);
        $entityManager->flush();
        $this->qrcode($newCode);
        return $this->render('coupon/qr_code.html.twig', [
            'newCode' => $newCode,
            'qrCodeDataUri'=>$newCode
        ]);}

    #[Route('/transform3', name: 'AffecterCouponExclusif')]
    public function AffecterCouponExclusif(ManagerRegistry $doctrine, Request $request, Security $security): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorieRepository = $entityManager->getRepository(categorieCoupon::class);
        $categorie = $categorieRepository->find(3); // Assumption: category with ID 1 exists
        $lastCoupon = $entityManager->getRepository(Coupon::class)->findOneBy(['id_categorie' => 3], ['id' => 'DESC']);
        $lastCode = $lastCoupon ? $lastCoupon->getCode() : 0;
        $newCode =  intval($lastCode) . "ExcluMaiCoupon" . "1";
        $couponExclusif = new Coupon();
        $couponExclusif->setIdCategorie($categorie);
        $couponExclusif->setTitreCoupon('Coupon Mai Exclusif');
        $couponExclusif->setDescriptionCoupon('Carte de recharge gratuite!');
        $couponExclusif->setDateExpiration(new \DateTime('2021-05-31'));
        $couponExclusif->setEtatCoupon("VALID");
        $couponExclusif->setCode($newCode);
        $user = $security->getUser();
        $couponExclusif->setIdUser($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($couponExclusif);
        $entityManager->flush();
        $this->qrcode($newCode);
        return $this->render('coupon/qr_code.html.twig', [
            'newCode' => $newCode,
        ]);
    }

    
    #[Route('/qrcode', name: 'qrcode')]
    public function qrcode(string $code): Response
    {
        $qrCode = new QrCode($code);
        $qrCode->setEncoding(new Encoding('UTF-8')); // Set the encoding
        $qrCodeDataUri = $qrCode->getData(); // Get the QR code data URI

        return $this->render('coupon/qr_code.html.twig', [
            'qrCodeDataUri' => $qrCodeDataUri,
        ]);

    }

}
