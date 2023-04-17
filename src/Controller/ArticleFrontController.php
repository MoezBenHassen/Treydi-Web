<?php

namespace App\Controller;

use App\Entity\ArticleRatings;
use App\Form\ArticleRatingsType;
use App\Repository\ArticleRatingsRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategorieArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleFrontController extends AbstractController
{
    #[Route('/article/', name: 'app_article_front')]
    public function index(ArticleRepository $articleRepository, CategorieArticleRepository $categorieArticleRepository): Response
    {
        /*count each categorie order them ASC*/

        $categories = $categorieArticleRepository->findBy(['archived' => false]);
        foreach ($categories as $categorie) {
            $categorie->setCount($articleRepository->count(['id_categorie' => $categorie->getId(), 'archived' => false]));
        }
        /*sort bigger first*/
        usort($categories, function ($a, $b) {
            return $a->getCount() < $b->getCount();
        });


        return $this->render('article_front/index.html.twig', [
            'articles' => $articleRepository->findBy(['archived' => false]),
            'categories' => $categories,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_front_show')]
    public function show(Request $request, ArticleRatingsRepository $articleRatingsRepository ,ArticleRepository $articleRepository, $id, CategorieArticleRepository $categorieArticleRepository): Response
    {
        $categories = $categorieArticleRepository->findBy(['archived' => false]);
        foreach ($categories as $categorie) {
            $categorie->setCount($articleRepository->count(['id_categorie' => $categorie->getId(), 'archived' => false]));
        }

        /*sort bigger first*/
        usort($categories, function ($a, $b) {
            return $a->getCount() < $b->getCount();
        });

        /*get auteur avatarUrl*/
        $article = $articleRepository->find($id);
        $auteur = $article->getIdUser();
        $avatarUrl = $auteur->getAvatarUrl();

        /*article ratings form*/
        $articleRating = new ArticleRatings();
        $form = $this->createForm(ArticleRatingsType::class, $articleRating);

        /* star rating form submission*/
        $form->handleRequest($request);
        dump($id, $this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            /*set the current article id  + the voters id ( current user session id )*/
            $articleRating->setIdArticle($article);
            $articleRating->setIdUser($this->getUser());
            /*save the article rating in the database*/
            $articleRatingsRepository->save($articleRating,true);

            /*update the avgRating in article table with the new average rating from the ArticleRatings table*/
            $article->setAvgRating($articleRatingsRepository->getAvgRating($id));
            $articleRepository->save($article,true);
            /*redirect to the article page*/
            return $this->render('article_front/show.html.twig', [
                'article' => $articleRepository->find($id),
                'articles' => $articleRepository->findBy(['archived' => false]),
                'categories' => $categories,
                'auteurAvatarUrl' => $avatarUrl,
                'form2' => $form->createView(),
            ]);
        }
        return $this->render('article_front/show.html.twig', [
            'article' => $articleRepository->find($id),
            'articles' => $articleRepository->findBy(['archived' => false]),
            'categories' => $categories,
            'auteurAvatarUrl' => $avatarUrl,
            'form2' => $form->createView(),
        ]);
    }
}
