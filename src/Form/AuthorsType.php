<?php

namespace App\Form;

use App\Entity\Authors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
                    'placeholder' => 'flen ben foulen'
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
                ]])
            ->add('imageFile',VichImageType::class,[
                'required' => false,
                'label' => 'Image de l\'auteur',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-fullname'],
                'allow_delete' => false,
                'delete_label' => 'Supprimer l\'image',
                'download_uri' => false,
                'attr' => [
                    'class' => 'form-control ',
                    'id' => 'basic-default-fullname',
                    'placeholder' => 'Sélectionnez une image',
                    'data-check' => 'true',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => '<i class="fa-solid fa-cloud-arrow-up"></i>',
                'label_html' => true,
                'attr' => ['class' => 'btn btn-label-primary mt-3', 'style' => 'font-size: 30px;']
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
