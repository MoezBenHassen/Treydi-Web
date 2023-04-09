<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\CategorieArticle;
use App\Entity\CategorieCoupon;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //titre with label
            ->add('titre',TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-titre'],
                'attr' => ['class' => 'form-control', 'id' => 'basic-default-titre',
                    'placeholder' => 'Titre de l\'article']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-decription'],
                'attr' => ['class' => 'form-control', 'id' => 'basic-default-description',
                    'placeholder' => 'Titre de l\'article'],
            ])
            ->add('contenu',TextareaType::class, [
                'label' => 'Contenu',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-contenu'],
                'attr' => ['class' => 'form-control', 'id' => 'basic-default-contenu',
                    'placeholder' => 'Titre de l\'article'],
            ])
            ->add('date_publication', DateType::class, [
                'label' => 'Date de publication',
                'label_attr' => ['class' => 'form-label ', 'for' => 'bs-datepicker-basic'],
                'attr' => ['class' => 'form-control',
                    'id' => 'bs-datepicker-basic',
                    'placeholder' => 'YYYY-MM-DD'],
            ])
            ->add('auteur', TextType::class,[
                'label' => 'Auteur',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-auteur'],
                'attr' => ['class' => 'form-control', 'id' => 'basic-default-auteur',
                    'placeholder' => 'Titre de l\'article'],
            ])
            //choice type for categories with label automattically loaded from database
            ->add('id_categorie', EntityType::class,[
                'class' => CategorieArticle::class,
                'choice_label' => 'libelle_cat',
                'label' => 'Catégorie',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-fullname'],
            ])
            ->add('id_user', EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => 'nom',
                'label' => 'Utilisateur',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-fullname'],
            ])
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
            'data_class' => Article::class,
        ]);
    }
}
