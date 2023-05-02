<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'choices' => [
                    'Trader' => 'ROLE_TRADER',
                    'Livreur' => 'ROLE_LIVREUR',
                    'Admin' => 'ROLE_ADMIN' ,
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('score')
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
