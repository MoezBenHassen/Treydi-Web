<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleRatings;
use App\Entity\Reponse;
use App\Form\ArticleRatingsType;
use App\Form\SearchArticlesFormType;
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
    public function index(string $articleCategory = null,Request $request, ArticleRepository $articleRepository, CategorieArticleRepository $categorieArticleRepository): Response
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

        $searchForm = $this->createForm(SearchArticlesFormType::class);
        $searchForm->handleRequest($request);
        $search = null;
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $search = $searchForm->get('search')->getData();
            $queryArticleList = $articleRepository->findByTitleAndDescriptionAndDate($search, null, false);
            $articleList = $queryArticleList;
        } else {
            $queryArticleList = $articleRepository->findByArchived(false);
            $articleList = $queryArticleList;
        }



        /*find articles with articleCategory if it exists using findBy*/
        if ($articleCategory) {
            $articleCategory = $categorieArticleRepository->findOneBy(['libelle_cat' => $articleCategory]);
            if ($articleCategory) {
                return $this->render('article_front/index.html.twig', [
                    'articles' => $articleRepository->findBy(['id_categorie' => $articleCategory->getId(), 'archived' => false]),
                    'categories' => $categories,
                    'articleCategory' => $articleCategory,
                    'searchForm' => $searchForm->createView(),
                ]);
            }
        }


        return $this->render('article_front/index.html.twig', [
            'articles' => $articleList,
            'categories' => $categories,
            'articleCategory' => $articleCategory,
            'searchForm' => $searchForm->createView(),
        ]);

    }

    #[Route('/article/show/{id<\d+>}', name: 'app_article_front_show')]
    public function show(Article $article ,Request $request, ArticleRatingsRepository $articleRatingsRepository ,ArticleRepository $articleRepository,int $id, CategorieArticleRepository $categorieArticleRepository): Response
    {

        // ###################  REPLACED BY SENSION BUNDLE FRAMEWORK BUNDLE : BY CALLING THE ARTICLE ENTITY AS A PARAMETER IN THE FUNCTION ######################
        /*
          $shownArticle =$articleRepository->find($id);
            if (!$shownArticle){
                throw $this->createNotFoundException('Article not found');
            }
        */
        // #######################################################################################################################################################
        /*COUNT HOW MANY ARTICLES IN EACH CATEGORY THEN SORT THEM IN ASC*/
        $categories = $categorieArticleRepository->findBy(['archived' => false]);
        foreach ($categories as $categorie) {
            $categorie->setCount($articleRepository->count(['id_categorie' => $categorie->getId(), 'archived' => false]));
        }
        ///*sort bigger first*/
        usort($categories, function ($a, $b) {
            return $a->getCount() < $b->getCount();
        });

        /*get auteur avatarUrl*/
        $article = $articleRepository->find($id);
        $auteur = $article->getIdUser();


        /*get the authors picture*/
        $auteur = $article->getAuteur();
        $authorPicture = $auteur->getImageName();
        $authorDescription = $auteur->getDescription();
        dump($authorPicture, $auteur);

        // CREATE ARTICLE_RATINGS FORM
        $articleRating = new ArticleRatings();
        $form = $this->createForm(ArticleRatingsType::class, $articleRating);
        $userVote = $articleRatingsRepository->findOneBy(['id_article' => $id, 'id_user' => $this->getUser()]);
        // CREATE SEARCH FORM
        $searchForm = $this->createForm(SearchArticlesFormType::class);
        $searchForm->handleRequest($request);
        $search = null;
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $search = $searchForm->get('search')->getData();
            $queryArticleList = $articleRepository->findByTitleAndDescriptionAndDate($search, null, false);
            $articleList = $queryArticleList;
        } else {
            $queryArticleList = $articleRepository->findByArchived(false);
            $articleList = $queryArticleList;
        }
        /* star rating form submission*/

        //################################## ARTICLE_RATINGS FORM SUBMISSION HANDLING ##################################
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //set the current article id  + the voters id ( current user session id )
                $articleRating->setIdArticle($article);
                $articleRating->setIdUser($this->getUser());
            //save the article rating of the user in the database
                $articleRatingsRepository->save($articleRating,true);

            //UPDATE the avgRating in article table with the new average rating from the ArticleRatings table //
                $article->setAvgRating($articleRatingsRepository->getAvgRating($id));
                $articleRepository->save($article,true);
                $this->addFlash('voteSuccess', 'Votre vote a été pris en compte !');

            /*GET THE USERS VOTE  from article_ratings FOR DISPLAY*/
                $userVote = $articleRatingsRepository->findOneBy(['id_article' => $id, 'id_user' => $this->getUser()]);
                dump($userVote);
            /*redirect to the article page*/
            return $this->render('article_front/show.html.twig', [
                'article' => $articleRepository->find($id),
                'articles' => $articleRepository->findBy(['archived' => false]),
                'categories' => $categories,
                'auteurAvatarUrl' => $authorPicture,
                'form2' => $form->createView(),
                'userVote' => $userVote->getRating(),
                'searchForm' => $searchForm->createView(),
                'authorPicture' => $authorPicture,
                'authorDescription' => $authorDescription,
            ]);
        }
        //##############################################################################################################


        return $this->render('article_front/show.html.twig', [
            //auto fetch the article with the id in the url
            'article' => $article,
            'articles' => $articleRepository->findBy(['archived' => false]),
            'categories' => $categories,
            'auteurAvatarUrl' => $authorPicture,
            'form2' => $form->createView(),
            'userVote' => ($userVote === null || $userVote->getRating() === 0) ? '&#248;' : $userVote->getRating(),
            'searchForm' => $searchForm->createView(),
            'authorPicture' => $authorPicture,
            'authorDescription' => $authorDescription,
        ]);
    }

    public function ratingForm($form){

    }
}
