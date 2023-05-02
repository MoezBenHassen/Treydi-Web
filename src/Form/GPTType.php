<?php
declare(strict_types=1);
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GPTType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prompt', TextareaType::class, [
                'label' => 'Prompt',
                'attr' => [
                    'placeholder' => 'Enter a prompt',
                    'class' => 'form-control', 'id' => 'basic-default-description',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Generate',
                'attr' => [
                    'class' => 'btn btn-label-primary ', 'style' => ''
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
