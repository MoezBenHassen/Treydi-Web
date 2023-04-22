<?php

namespace App\Form;

use App\Entity\CategorieCoupon;
use App\Entity\Coupon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreCoupon')
            ->add('descriptionCoupon')
            ->add('dateExpiration')
            ->add('archived')
            ->add('idCategorie', EntityType::class, [
                'required' => false,
                'class' => CategorieCoupon::class,
                'label' => 'Category',
                'placeholder' => 'All categories'
            ])
            ->add('etat_coupon', CheckboxType::class, [
                'required' => false,
                'label' => 'Valide',
            ])
            ->add('archived', CheckboxType::class, [
                'required' => false,
                'label' => 'Archivé',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coupon::class,
        ]);
    }
}
