<?php

namespace App\Controller;

use App\Entity\Item;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EchangeController extends AbstractController
{
    #[Route('/echange', name: 'app_echange')]
    public function index(): Response
    {
        return $this->render('echange/index.html.twig', [
            'controller_name' => 'EchangeController',
        ]);
    }

    #[Route('/echange/creer', name: 'app_echange')]
    public function creer(ManagerRegistry $doctrine): Response
    {
        $user_items = $doctrine
            ->getRepository(Item::class)
            ->findAllItemsForEchange();

        return $this->render('echange/creer.html.twig', [
            'user_items' => $user_items,
        ]);
    }
}
