<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltrereclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_reclamation')
            ->add('description_reclamation')
            ->add('etatReclamation', ChoiceType::class, [
                'required' => false,
                'label' => 'État de la réclamation',
                'attr' => ['class' => 'form-control'],
                'choices' => [
                    'En cours' => 'En cours',
                    'Traité' => 'Traité',
                ],
            ])
            ->add('date_creation', DateType::class, [
                'label' => 'date creation',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control input-sm',
                ],
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
