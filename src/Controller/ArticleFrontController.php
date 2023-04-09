<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleFrontController extends AbstractController
{
    #[Route('/article/', name: 'app_article_front')]
    public function index(): Response
    {
        return $this->render('article_front/index.html.twig', [
            'controller_name' => 'ArticleFrontController',
        ]);
    }
}
