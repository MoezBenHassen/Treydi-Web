<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\UpdateformType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationUserController extends AbstractController
{
    #[Route('/reclamation/user', name: 'app_reclamation_user')]
    public function index(): Response
    {
        return $this->render('reclamation_user/index.html.twig', [
            'controller_name' => 'ReclamationUserController',
        ]);
    }
    #[Route('/reclamation/addUser', name: 'app_reclamationUserAdd')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $req = new Reclamation();
       // $req->setIdUser($this->getUser());
        $req->setArchived(0);
        $req->setEtatReclamation("en cours");
        $req->setDateCreation(new DateTime());
        $form = $this->createForm(ReclamationType::class, $req);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($req);
            $em->flush();
            return $this->redirectToRoute('app_reclamationUserAdd');
        }
        return $this->renderForm('reclamation_user/addReclamationUser.html.twig', array('formA' => $form));
    }

    #[Route('/reclamation/listUser', name: 'app_reclamationUserList', methods: ['GET', 'POST'])]
    public function list(Request $request,ManagerRegistry $doctrine): Response
    {

          $repository = $doctrine->getRepository(Reclamation::class);

        $search = $request->query->get('search');
        $dateCreation = $request->query->get('dateCreation');
        $query = $repository->findByTitreEtDescriptionEtDateCreationUser(false, $search, $dateCreation);
        $list = $query;

        return $this->render('reclamation_user/showReclamationUser.html.twig', [
            'controller_name' => 'ReclamationListController',
            'list' => $list,
            'search' => $search,
            'dateCreation' => $dateCreation,

        ]);
    }

    #[Route('/reclamation/deleteUser/{id}', name: 'app_reclamationUserDelete', methods: ['GET','POST'])]
    public function deleteReclamation(Reclamation $reclamation, ManagerRegistry $doctrine): Response
    {
        $reclamation->setArchived(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('app_reclamationUserList');
    }

    #[Route('/reclamation/updateUser/{id}', name: 'app_reclamationUpdateUser',  methods: ['GET','POST'])]
    public function update(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);

        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        $form = $this->createForm(UpdateformType::class, $reclamation);
        $form->add('modifier', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_reclamationUserList');
        }

        return $this->renderForm('reclamation_user/updateReclamationUser.html.twig', ['formU' => $form]);
    }



}
