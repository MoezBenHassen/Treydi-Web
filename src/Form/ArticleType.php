<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Authors;
use App\Entity\CategorieArticle;
use App\Entity\CategorieCoupon;
use App\Entity\Utilisateur;
use App\Repository\CategorieArticleRepository;
use Brokoskokoli\StarRatingBundle\Form\RatingType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
                'attr' => [
                    'class' => 'form-control', 'id' => 'basic-default-description',
                    'placeholder' => 'Description de l\'article',
                    'style' => 'height: 100px',
                ],
            ])
            /*imageFile*/
            ->add('contenu',TextareaType::class, [
                'label' => 'Contenu',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-contenu'],
                'attr' => [
                    'class' => 'form-control', 'id' => 'basic-default-contenu',
                    'placeholder' => 'Contenu de l\'article',
                    'style' => 'height: 300px',
                ],
            ])
                ->add('date_publication', DateType::class, [
                'label' => 'Date de publication',
                'label_attr' => ['class' => 'form-label ', 'for' => 'bs-datepicker-basic'],
                'attr' => ['class' => 'form-control',
                    'id' => 'bs-datepicker-basic',
                    'placeholder' => 'YYYY-MM-DD'],
                'widget' => 'single_text',
            ])
            ->add('auteur', EntityType::class,[
                'class' => Authors::class,
                'label' => 'Auteur',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-auteur'],
                'attr' => ['class' => 'form-control', 'id' => 'basic-default-auteur',
                    'placeholder' => 'Auteur de l\'article'],
            ])
            //choice type for categories with label automattically loaded from database
            //only categories with archived = 0 are displayed
            ->add('id_categorie', EntityType::class,[
                'class' => CategorieArticle::class,
                'query_builder' => function (CategorieArticleRepository $categorieArticle) {
                    return $categorieArticle->createQueryBuilder('c')
                        ->where('c.archived = 0');
                },
                'choice_label' => 'libelle_cat',
                'label' => 'Catégorie',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-fullname'],
                'attr' => ['class' => 'form-select', 'id' => 'basic-default-fullname',
                    'placeholder' => 'Sélectionnez une catégorie'],
                'placeholder' => 'Sélectionnez une catégorie',
            ])
            ->add('id_user', EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => 'nom',
                'label' => 'Utilisateur',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-fullname'],
                'attr' => ['class' => 'form-select', 'id' => 'basic-default-fullname',
                    'placeholder' => 'Sélectionnez un utilisateur'],
                'placeholder' => 'Sélectionnez un utilisateur',
            ])
            ->add('imageFile',VichImageType::class,[
                'required' => false,
                'label' => 'Image de l\'article',
                'label_attr' => ['class' => 'form-label ', 'for' => 'basic-default-fullname'],
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'basic-default-fullname',
                    'placeholder' => 'Sélectionnez une image',
                ],
            ])
            /*->add('avgRating', NumberType::class, [
                'label' => 'Rating',
            ])*/
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
