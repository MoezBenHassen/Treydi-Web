<?php

namespace App\Form;

use App\Entity\Coupon;
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
            ->add('etat_coupon')
            ->add('code')
            ->add('archived')
            ->add('id_user')
            ->add('id_categorie')
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
