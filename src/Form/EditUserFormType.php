<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('date_naissance', BirthdayType::class, [
                'label' => 'Date de naissance',
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control dob-picker',
                    'id' => 'multicol-country',
                    'data-allow-clear' => 'true'
                ],
                'format' => 'yyyy-MM-dd',
                'placeholder' => [
                    'day' => 'Jour', 'month' => 'Mois', 'year' => 'Année',
                ]
            ])
            ->add('imageFile',VichImageType::class,[
                'required' => false,
                'label' => 'Image de l\'article',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-fullname'],
                'delete_label' => 'Supprimer l\'image',
                'download_uri' => true,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'basic-default-fullname',
                    'placeholder' => 'Sélectionnez une image',
                    'data-check' => 'true',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
