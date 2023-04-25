<?php

namespace App\Form;

use App\Entity\CategorieCoupon;
use App\Entity\Coupon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_coupon', TextType::class, [
                'required' => false,
                'label' => 'Nom du Coupon',
            ])
            ->add('description_coupon', TextType::class, [
            'required' => false,
             'label' => 'Description du Coupon',
            ])
            ->add('date_expiration', DateType::class, [
                'required' => false,
                'label' => 'Date Expiration du Coupon',
            ])
            ->add('id_categorie', EntityType::class, [
                'required' => false,
                'class' => CategorieCoupon::class,
                'label' => 'Category',
                'placeholder' => 'All categories'
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
        ]);
    }
}
