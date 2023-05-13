<?php

namespace App\Controller;

use App\Entity\CategorieCoupon;
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
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use App\Controller\SecurityController;

class ControllerCodeNameOne extends AbstractController {

    #[Route('/addcoupon', name: 'app_coupon_ajout')]
    public function ajouter(Request $request): Response
    {
        
        $coupon = new Coupon();
        $titre_coupon= $request->query->get('titre_coupon');
        $description_coupon=$request->query->get('description_coupon');
        $code=$request->query->get('code');
        $em=$this->getDoctrine()->getManager();
        $date_expiration = new \DateTime('');
        $date_expiration->modify('last day of this month');
        $coupon->setTitreCoupon($titre_coupon);
        $coupon->setDescriptionCoupon($description_coupon);
        $coupon->setCode($code);
        $coupon->setDateExpiration($date_expiration);
        $coupon->setEtatCoupon("VALID");
        $coupon->setArchived(0);
        $em->persist($coupon);
        $em->flush();
        $normalizers = [new ObjectNormalizer()];
            $encoders = [new JsonEncoder()];
            $serializer = new Serializer($normalizers, $encoders);
            $formatted = $serializer->serialize($coupon, 'json');
            return new JsonResponse($formatted);
    
    }

    
    #[Route('/displaycouponuser/{id}', name: 'app_coupon_display', methods: ['POST', 'GET'])]
    public function display($id, CouponRepository $repository): JsonResponse
    {
        $list = $repository->findBy([
            'archived' => false,
            'id_user' => $id,
        ]);
        
        $CouponsArray = array_map(function (Coupon $rec) {
            return [
                'titre_coupon' => $rec->getTitre_Coupon(),
                'description_coupon' => $rec->getDescription_Coupon(),
                'etat_coupon' => $rec->getEtat_Coupon(),
                'date_expiration' => $rec->getDate_Expiration(),
                'code' => $rec->getCode(),
                'archived' => $rec->isArchived(),
            ];
        }, $list);
        
        // create a JSON response containing the items array
        return new JsonResponse(['coupons' => $CouponsArray]);
    }
    

    
    
    #[Route('/scores', name: 'app_coupon_scores',  methods: ['POST', 'GET'])]
        public function scoreboard(ManagerRegistry $doctrine): Response
        {
            $userRepository = $doctrine->getRepository(Utilisateur::class);
            $users = $userRepository->findBy([], ['score' => 'DESC']);
    
            $UsersArray = array_map(function (Utilisateur $u) {
                $score = $u->getScore();
                if ($score === null) {
                    $score = 0;
                }
                return [
                    'nom' => $u->getNom(),
                    'prenom' => $u->getPrenom(),
                    'score' => $score,
                ];
            }, $users);
            return new JsonResponse(['users' => $UsersArray]);
                
        }
    
    }