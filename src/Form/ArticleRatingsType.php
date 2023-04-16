<?php

namespace App\Form;

use App\Entity\ArticleRatings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleRatingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', NumberType::class, [
                'label' => 'Rating',
                /*make the field hidden with an id of avgRatingInput*/
                'attr' => ['class' => 'avgRatingInput', 'hidden' => false],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleRatings::class,
        ]);
    }
}
