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

    #[Route('/reponse/listUser/{id}', name: 'app_reponseUserList', methods: ['GET', 'POST'])]
    public function list(ManagerRegistry $doctrine,int $id): Response
    {


       // $query = $repository->createQueryBuilder('r')
         //   ->where('r.archived = :archived')
           // ->andWhere('r.id_reclamation = :idr')
        //    ->setParameter('archived', 0)
           // ->setParameter('idr', $idr)
        //    ->getQuery();
           //     $listr = $query->getResult();

        $repository = $doctrine->getRepository(Reponse::class);
        $listr = $repository->listReponseparReclamation($id);

        return $this->render('reponse_user/showReponseUser.html.twig', [
            'controller_name' => 'ReponseListUserController',
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
