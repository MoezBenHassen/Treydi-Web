<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\CategorieItems;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('libelle')
    ->add('description', TextareaType::class, [
        'attr' => ['rows' => 5, 'cols' => 40],
    ])
    ->add('etat', ChoiceType::class, [
        'choices' => [
            'Neuf' => 'Neuf',
            'Occasion' => 'Occasion',
        ],
    ])
    ->add('type', ChoiceType::class, [
        'choices' => [
            'Physique' => 'Physique',
            'Virtuelle' => 'Virtuelle',
            'Service' => 'Service',
        ],
    ])
    ->add('imageurl')
    ->add(
        'id_categorie',
        EntityType::class,
        [
            'class' => CategorieItems::class,
            'choice_label' => 'nom_categorie',
            'multiple' => false,
            'expanded' => false,
        ]
        );

;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
