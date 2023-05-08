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
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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


#[Route('/displaycoupon', name: 'app_coupon_display')]
public function display(Request $request): Response
{
    $coupon = $this->getDoctrine()->getRepository(Coupon::class)->findAll();
    $serializer= new Serializer([new ObjectNormalizer()]);
    $formatted=$serializer->normalize($coupon);
    return new JsonResponse($formatted);

}
}
