<?php

namespace App\Controller;

use App\Entity\Reponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReponseUserController extends AbstractController
{
    #[Route('/reponse/user', name: 'app_reponse_user')]
    public function index(): Response
    {
        return $this->render('reponse_user/index.html.twig', [
            'controller_name' => 'ReponseUserController',
        ]);
    }

    #[Route('/reponse/listUser', name: 'app_reponseUserList', methods: ['GET', 'POST'])]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reponse::class);

        $query = $repository->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->setParameter('archived', 0)
            ->getQuery();
        $listr = $query->getResult();

        return $this->render('reponse_user/showReponseUser.html.twig', [
            'controller_name' => 'ReponseListController',
            'listr' => $listr
        ]);
    }

    #[Route('/reponse/deleteUser/{id}', name: 'app_reponseUserDelete', methods: ['GET','POST'])]
    public function deleteReclamation(Reponse $reponse ,ManagerRegistry $doctrine): Response
    {
        // Set the "archived" attribute to 1 to indicate that the reclamation has been archived
        $reponse->setArchived(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('app_reponseUserList');
    }



}
