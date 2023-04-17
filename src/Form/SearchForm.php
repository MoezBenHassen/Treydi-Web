<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'label' => 'Search for a coupon:',
                'required' => false
            ])
            ->add('archived', ChoiceType::class, [
                'label' => 'Archivé?:',
                'choices' => [
                    'All' => null,
                    'Archivé' => true,
                    'Non Archivé' => false
                ],
                'required' => false
            ])
            ->add('isValid', ChoiceType::class, [
                'label' => 'Validité du coupon:',
                'choices' => [
                    'All' => null,
                    'VALID' => true,
                    'NOT VALID' => false
                ],
                'required' => false
            ])
        ->add('categorie', EntityType::class, [
        'label' => 'Catégorie du coupon:',
        'class' => Categorie::class,
        'choice_label' => 'nom_catégorie',
        'required' => false,
        'placeholder' => 'Toutes les catégories',
        'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('c')
                ->orderBy('c.nom_catégorie', 'ASC');
        },
    ]);
}

}