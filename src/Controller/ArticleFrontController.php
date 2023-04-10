<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleFrontController extends AbstractController
{
    #[Route('/article/', name: 'app_article_front')]
    public function index(ArticleRepository $articleRepository): Response
    {

        return $this->render('article_front/index.html.twig', [
            'articles' => $articleRepository->findBy(['archived' => false]),
        ]);
    }

}
