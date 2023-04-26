<?php

namespace App\Form;

use App\Entity\CategorieArticle;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class CategorieArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle_cat', TextType::class, [
                'label' => 'Libellé',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-titre'],
                'attr' => ['class' => 'form-control', 'id' => 'basic-default-titre',
                'placeholder' => 'Catégorie']
            ])
            //add submit button
            //add submit button
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
            'data_class' => CategorieArticle::class,
        ]);
    }
}
