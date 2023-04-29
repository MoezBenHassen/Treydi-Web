<?php

namespace App\Form;

use App\Entity\CategorieCoupon;
use App\Entity\Coupon;
use App\Entity\Utilisateur;
use App\Repository\CategorieCouponRepository;
use App\Repository\CouponRepository;
use Monolog\Handler\Curl\Util;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_coupon')
            ->add('description_coupon')
            ->add('date_expiration')
            ->add('code')
            ->add('id_user', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'prenom',
            ])
            ->add('id_categorie', EntityType::class, [
                'class' => CategorieCoupon::class,
                'choice_label' => 'nom_categorie',
                'query_builder' => function (CategorieCouponRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.archived = :archived')
                        ->setParameter('archived', false);
                },
            ])
            ->add('add', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coupon::class,
        ]);
    }
}
