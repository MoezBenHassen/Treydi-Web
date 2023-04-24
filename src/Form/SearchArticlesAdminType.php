<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchArticlesAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'label' => 'Rechercher',
                'label_attr' => ['class' => 'form-label', 'for' => 'basic-default-titre'],
                'required' => false,
                'attr' => [
                    'class' => 'form-control input-sm',
                    'name' => 'search',
                    'id' => 'search',
                ],
            ])
            ->add('date_publication', DateType::class, [
                'label' => true,
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control input-sm',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-primary btn-sm',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            // Configure your form options here
        ]);
    }
}
