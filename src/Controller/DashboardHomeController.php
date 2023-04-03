<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardHomeController extends AbstractController
{
    #[Route('/dashboard/home', name: 'app_dashboard_home')]
    public function index(): Response
    {
        return $this->render('dashboard_home/index.html.twig', [
            'controller_name' => 'DashboardHomeController',
        ]);
    }
}
