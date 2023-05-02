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
        $user = $security->getUser();
        $userScore = $user->getScore();

    // Render the template, passing the user's score to it
        return $this->render('coupon/transformscore.html.twig', [
            'userScore' => $userScore,
        ]);
    }
    #[Route('/transform1', name: 'AffecterCouponMensuel')]
    public function AffecterCouponMensuel(ManagerRegistry $doctrine, Request $request, Security $security): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorieRepository = $entityManager->getRepository(categorieCoupon::class);
        $categorie = $categorieRepository->find(1); // Assumption: category with ID 1 exists
        $couponRepository = $entityManager->getRepository(Coupon::class);
        $lastCoupon = $couponRepository->findOneBy(['code' => '%CasMaiCoupon%'], ['id' => 'DESC']);

        $lastNumber = 0;
        if ($lastCoupon) {
            $lastCode = $lastCoupon->getCode();
            dd($lastCode);
            $lastNumber = intval(preg_replace('/[^0-9]/', '', substr($lastCode, 12)));
            dd("chose".$lastNumber);
        }
        dd("chose2".$lastNumber);
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
        $user->setScore($user->getScore() - 1000);
        if($user->getScore() < 0){
          $user->setScore(0);
        }
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
        $categorieRepository = $entityManager->getRepository(CategorieCoupon::class);
        $categorie = $categorieRepository->find(2); // Assumption: category with ID 2 exists
        $couponRepository = $entityManager->getRepository(Coupon::class);
        $lastCoupon = $couponRepository->createQueryBuilder('c')
            ->where('c.code LIKE :code')
            ->setParameter('code', 'SpecMaiCoupon%')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $newNumber = 1;
        if ($lastCoupon !== null) {
            $lastCode = $lastCoupon->getCode();
            $lastNumber = intval(substr($lastCode, 12));
            $newNumber = $lastNumber + 1;
        }

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
        $user->setScore($user->getScore() - 2000);
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
        $categorie = $categorieRepository->find(3); // Assumption: category with ID 3 exists
        $couponRepository = $entityManager->getRepository(Coupon::class);
        $lastCoupon = $couponRepository->createQueryBuilder('c')
            ->where('c.code LIKE :code')
            ->setParameter('code', 'ExcluMaiCoupon%')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($lastCoupon === null) {
            $newNumber = 1;
        } else {
            $lastCode = $lastCoupon->getCode();
            $lastNumber = intval(substr($lastCode, 13));
            $newNumber = $lastNumber + 1;
        }

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
        $user->setScore($user->getScore() - 5000);
        $entityManager->persist($couponExclusif);
        $entityManager->flush();
        $user->setScore($user->getScore() - 5000);
        $qrCodeResponse = $this->forward(qrcode::class.'::qrcode', [
            'code' => $newCode,
        ]);

        $qrCodeDataUri = $qrCodeResponse->getContent();

        // return the template with the data URI
        return $this->render('coupon/qr_code.html.twig', [
            'qrCodeDataUri' => $qrCodeDataUri,
        ]);
    }






}


