<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleRatings;
use App\Entity\Reponse;
use App\Form\ArticleRatingsType;
use App\Repository\ArticleRatingsRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategorieArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleFrontController extends AbstractController
{
    #[Route('/article/{articleCategory?}', name: 'app_article_front')]
    public function index(string $articleCategory = null, ArticleRepository $articleRepository, CategorieArticleRepository $categorieArticleRepository): Response
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
        /*find articles with articleCategory if it exists using findBy*/
        if ($articleCategory) {
            $articleCategory = $categorieArticleRepository->findOneBy(['libelle_cat' => $articleCategory]);
            if ($articleCategory) {
                return $this->render('article_front/index.html.twig', [
                    'articles' => $articleRepository->findBy(['id_categorie' => $articleCategory->getId(), 'archived' => false]),
                    'categories' => $categories,
                    'articleCategory' => $articleCategory,
                ]);
            }
        }
        return $this->render('article_front/index.html.twig', [
            'articles' => $articleRepository->findBy(['archived' => false]),
            'categories' => $categories,
            'articleCategory' => $articleCategory,
        ]);

    }

    #[Route('/article/show/{id<\d+>}', name: 'app_article_front_show')]
    public function show(Article $article ,Request $request, ArticleRatingsRepository $articleRatingsRepository ,ArticleRepository $articleRepository,int $id, CategorieArticleRepository $categorieArticleRepository): Response
    {
        // ###################  REPLACED BY SENSION BUNDLE FRAMEWORK BUNDLE : BY CALLING THE ARTICLE ENTITY AS A PARAMETER IN THE FUNCTION ###########
    /*

      $shownArticle =$articleRepository->find($id);
        if (!$shownArticle){
            throw $this->createNotFoundException('Article not found');
        }
    */
        /*count each categorie order them ASC*/
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
        $userVote = $articleRatingsRepository->findOneBy(['id_article' => $id, 'id_user' => $this->getUser()]);

        /* star rating form submission*/
        $form->handleRequest($request);
        dump($id, $this->getUser(), $userVote);
        if ($form->isSubmitted() && $form->isValid()) {
            /*set the current article id  + the voters id ( current user session id )*/
            $articleRating->setIdArticle($article);
            $articleRating->setIdUser($this->getUser());
            /*save the article rating of the user in the database*/
            $articleRatingsRepository->save($articleRating,true);

            /*update the avgRating in article table with the new average rating from the ArticleRatings table*/
            $article->setAvgRating($articleRatingsRepository->getAvgRating($id));
            $articleRepository->save($article,true);
            $this->addFlash('voteSuccess', 'Votre vote a été pris en compte !');

            /*get user vote from article_ratings*/
            $userVote = $articleRatingsRepository->findOneBy(['id_article' => $id, 'id_user' => $this->getUser()]);
            dump($userVote);
            /*redirect to the article page*/
            return $this->render('article_front/show.html.twig', [
                'article' => $articleRepository->find($id),
                'articles' => $articleRepository->findBy(['archived' => false]),
                'categories' => $categories,
                'auteurAvatarUrl' => $avatarUrl,
                'form2' => $form->createView(),
                'userVote' => $userVote->getRating(),
            ]);
        }

        return $this->render('article_front/show.html.twig', [
            //auto fetch the article with the id in the url
            'article' => $article,
            'articles' => $articleRepository->findBy(['archived' => false]),
            'categories' => $categories,
            'auteurAvatarUrl' => $avatarUrl,
            'form2' => $form->createView(),
            'userVote' => ($userVote === null || $userVote->getRating() === 0) ? '&#248;' : $userVote->getRating(),
        ]);
    }

}
