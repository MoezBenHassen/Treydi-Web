<?php

namespace App\Controller;

use App\Entity\CategorieArticle;
use App\Form\CategorieArticleType;
use App\Repository\CategorieArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/article')]
class CategorieArticleController extends AbstractController
{
    #[Route('/', name: 'app_categorie_article_index', methods: ['GET'])]
    public function index(CategorieArticleRepository $categorieArticleRepository): Response
    {
        return $this->render('categorie_article/index.html.twig', [
            'categorie_articles' => $categorieArticleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieArticleRepository $categorieArticleRepository): Response
    {
        $categorieArticle = new CategorieArticle();
        $form = $this->createForm(CategorieArticleType::class, $categorieArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieArticleRepository->save($categorieArticle, true);

            return $this->redirectToRoute('app_categorie_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_article/new.html.twig', [
            'categorie_article' => $categorieArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_article_show', methods: ['GET'])]
    public function show(CategorieArticle $categorieArticle): Response
    {
        return $this->render('categorie_article/show.html.twig', [
            'categorie_article' => $categorieArticle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieArticle $categorieArticle, CategorieArticleRepository $categorieArticleRepository): Response
    {
        $form = $this->createForm(CategorieArticleType::class, $categorieArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieArticleRepository->save($categorieArticle, true);

            return $this->redirectToRoute('app_categorie_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_article/edit.html.twig', [
            'categorie_article' => $categorieArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_article_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieArticle $categorieArticle, CategorieArticleRepository $categorieArticleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieArticle->getId(), $request->request->get('_token'))) {
            $categorieArticleRepository->remove($categorieArticle, true);
        }

        return $this->redirectToRoute('app_categorie_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
