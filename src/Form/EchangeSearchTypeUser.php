<?php

namespace App\Form;

use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EchangeSearchTypeUser extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Rechercher titre Echange ici ...'],
            ])

            ->add('itemSearch', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Rechercher Item dans Echange ici ...'],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'flaticon-loupe']
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
