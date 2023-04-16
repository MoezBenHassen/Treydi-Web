<?php

namespace App\Controller;

use App\Entity\ArticleRatings;
use App\Form\ArticleRatingsType;
use App\Repository\ArticleRatingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article/ratings')]
class ArticleRatingsController extends AbstractController
{
    #[Route('/', name: 'app_article_ratings_index', methods: ['GET'])]
    public function index(ArticleRatingsRepository $articleRatingsRepository): Response
    {
        return $this->render('article_ratings/index.html.twig', [
            'article_ratings' => $articleRatingsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_ratings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRatingsRepository $articleRatingsRepository): Response
    {
        $articleRating = new ArticleRatings();
        $form = $this->createForm(ArticleRatingsType::class, $articleRating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRatingsRepository->save($articleRating, true);

            return $this->redirectToRoute('app_article_front_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article_ratings/new.html.twig', [
            'article_rating' => $articleRating,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_ratings_show', methods: ['GET'])]
    public function show(ArticleRatings $articleRating): Response
    {
        return $this->render('article_ratings/show.html.twig', [
            'article_rating' => $articleRating,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_ratings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ArticleRatings $articleRating, ArticleRatingsRepository $articleRatingsRepository): Response
    {
        $form = $this->createForm(ArticleRatingsType::class, $articleRating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRatingsRepository->save($articleRating, true);

            return $this->redirectToRoute('app_article_ratings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article_ratings/edit.html.twig', [
            'article_rating' => $articleRating,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_ratings_delete', methods: ['POST'])]
    public function delete(Request $request, ArticleRatings $articleRating, ArticleRatingsRepository $articleRatingsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleRating->getId(), $request->request->get('_token'))) {
            $articleRatingsRepository->remove($articleRating, true);
        }

        return $this->redirectToRoute('app_article_ratings_index', [], Response::HTTP_SEE_OTHER);
    }
}
