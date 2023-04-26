<?php

namespace App\Controller;

use App\Entity\CategorieCoupon;
use App\Entity\Coupon;
use App\Entity\Utilisateur;
use App\Controller\qrcode;
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
        $couponRepository = $entityManager->getRepository(Coupon::class);
        $lastCoupon = $couponRepository->createQueryBuilder('c')
        ->where('c.code LIKE :code')
        ->setParameter('code', 'CasMaiCoupon%')
        ->orderBy('c.id', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        $lastCode = $lastCoupon->getCode();
        $lastNumber = intval(substr($lastCode, 12));
        $newNumber = $lastNumber + 1;
        $newCode = "CasMaiCoupon" . $newNumber;

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
        $this->addFlash('success', 'Coupon Mensuel Affecté avec Succès');
         
        $qrCodeResponse = $this->forward(qrcode::class.'::qrcode', [
            'code' => $newCode,
        ]);
        
        // extract the data URI from the response
        $qrCodeDataUri = $qrCodeResponse->getContent();
        
        // return the template with the data URI
        return $this->render('coupon/qr_code.html.twig', [
            'qrCodeDataUri' => $qrCodeDataUri,
        ]);
    }


    #[Route('/transform2', name: 'AffecterCouponSpecial')]
    public function AffecterCouponSpecial(ManagerRegistry $doctrine, Request $request, Security $security): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorieRepository = $entityManager->getRepository(categorieCoupon::class);
        $categorie = $categorieRepository->find(2); // Assumption: category with ID 1 exists
        $couponRepository = $entityManager->getRepository(Coupon::class);
        $lastCoupon = $couponRepository->createQueryBuilder('c')
        ->where('c.code LIKE :code')
        ->setParameter('code', 'SpecMaiCoupon%')
        ->orderBy('c.id', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        $lastCode = $lastCoupon->getCode();
        $lastNumber = intval(substr($lastCode, 12));
        $newNumber = $lastNumber + 1;
        $newCode = "SpecMaiCoupon" . $newNumber;

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
        $qrCodeResponse = $this->forward(qrcode::class.'::qrcode', [
            'code' => $newCode,
        ]);
        
        // extract the data URI from the response
        $qrCodeDataUri = $qrCodeResponse->getContent();
        
        // return the template with the data URI
        return $this->render('coupon/qr_code.html.twig', [
            'qrCodeDataUri' => $qrCodeDataUri,
        ]);
        }

    #[Route('/transform3', name: 'AffecterCouponExclusif')]
    public function AffecterCouponExclusif(ManagerRegistry $doctrine, Request $request, Security $security): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorieRepository = $entityManager->getRepository(categorieCoupon::class);
        $categorie = $categorieRepository->find(3); // Assumption: category with ID 1 exists
        $couponRepository = $entityManager->getRepository(Coupon::class);
        $lastCoupon = $couponRepository->createQueryBuilder('c')
        ->where('c.code LIKE :code')
        ->setParameter('code', 'ExcluMaiCoupon%')
        ->orderBy('c.id', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult();

        $lastCode = $lastCoupon->getCode();
        $lastNumber = intval(substr($lastCode, 12));
        $newNumber = $lastNumber + 1;
        $newCode = "ExcluMaiCoupon" . $newNumber;

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
        $qrCodeResponse = $this->forward(qrcode::class.'::qrcode', [
            'newCode' => $newCode,
        ]);
        
        
        $qrCodeDataUri = $qrCodeResponse->getContent();
        
        // return the template with the data URI
        return $this->render('coupon/qr_code.html.twig', [
            'qrCodeDataUri' => $qrCodeDataUri,
        ]);
    }

    
   
 

}


