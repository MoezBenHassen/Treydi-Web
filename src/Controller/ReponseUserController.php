<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


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


    #[Route('/reponse/avis/{id}/{avis}', name: 'app_reponseUseravis', methods: ['POST','GET'])]
    public function updateSatisfaction(Request $request, ManagerRegistry $doctrine, int $id,string $avis): Response
    {
        $em = $doctrine->getManager();
        $reponse = $em->getRepository(Reponse::class)->find($id);

        // Get the value of avis from the request parameters
        $rec = $em->getRepository(Reclamation::class)->find($reponse->getIdReclamation());

        // Set the value of avis based on the request parameter value
        $reponse->setAvis($avis);
        $ide = $rec->getId();
        $em->persist($reponse);
        $em->flush();

        return $this->redirectToRoute('app_reponseUserList', ['id' => $ide ]);
    }
 #[Route('/reponse/listm', name: 'app_reponseList_m', methods: ['POST', 'GET'])]
    public function mobileL(ReponseRepository $repository,ManagerRegistry $doctrine): JsonResponse
    {
        $list = $repository->findBy(['archived' => false]);

        $reponseArray = array_map(function (Reponse $rec) {
            return [
                'id' => $rec->getId(),
                'titre_reponse' => $rec->getTitreReponse(),
                'description_reponse' => $rec->getDescriptionReponse(),
                'date_reponse' => $rec->getDateReponse(),
                'id_reclamation' => $rec->getIdReclamation(),
            ];
        }, $list);

        return new JsonResponse(['repon' => $reponseArray]);
    }


}
