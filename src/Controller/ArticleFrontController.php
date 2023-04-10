<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategorieArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(ArticleRepository $articleRepository, $id, CategorieArticleRepository $categorieArticleRepository): Response
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
        return $this->render('article_front/show.html.twig', [
            'article' => $articleRepository->find($id),
            'articles' => $articleRepository->findBy(['archived' => false]),
            'categories' => $categories,
            'auteurAvatarUrl' => $avatarUrl,
        ]);
    }


}
