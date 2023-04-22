<?php

namespace App\Form;

use App\Entity\Authors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FullName',TextType::class, [
                'label' => 'FullName',
                'label_attr' => [
                    'class' => 'form-label ',
                    'for' => 'basic-default-titre'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'basic-default-titre',
                    'placeholder' => 'FullName de l\'auteur'
                ]
            ])
            ->add('DateDeNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'label_attr' => [
                    'class' => 'form-label ',
                    'for' => 'bs-datepicker-basic'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'bs-datepicker-basic',
                    'placeholder' => 'YYYY-MM-DD'
                ],
                'widget' => 'single_text',
            ])
            ->add('Description', TextareaType::class, [
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-decription'],
                'attr' => [
                    'class' => 'form-control', 'id' => 'basic-default-description',
                    'placeholder' => 'Description de l\'article',
                    'style' => 'height: 100px',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Authors::class,
        ]);
    }
}
