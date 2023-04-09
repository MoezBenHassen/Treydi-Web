<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\UpdateformType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    #[Route('/reclamation/list', name: 'app_reclamationList', methods: ['GET', 'POST'])]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reclamation::class);

        //  $query = $repository->createQueryBuilder('r')
        //    ->where('r.archived = :archived')
        //  ->setParameter('archived', 0)
        //->getQuery();
        $query = $repository->findByArchived(false);
        $list = $query ;

        return $this->render('reclamation/show.html.twig', [
            'controller_name' => 'ReclamationListController',
            'list' => $list
        ]);
    }

    #[Route('/reclamation/add', name: 'app_reclamationAdd')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $req = new Reclamation();
        $req->setArchived(0); // set archived property to 0 (not archived)
        $req->setEtatReclamation("en cours");
        $req->setDateCreation(new DateTime());
        $form = $this->createForm(ReclamationType::class, $req);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($req);
            $em->flush();
            return $this->redirectToRoute('app_reclamationAdd');
        }

        return $this->renderForm('reclamation/add.html.twig', array('formA' => $form));
    }


    #[Route('/reclamation/delete/{id}', name: 'app_reclamationDelete', methods: ['POST','GET'])]
    public function deleteReclamation(Reclamation $reclamation, ManagerRegistry $doctrine): Response
    {
        $reclamation->setArchived(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('app_reclamationList');
    }
    #[Route('/reclamation/update/{id}', name: 'app_reclamationUpdate')]
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
            return $this->redirectToRoute('app_reclamationList');
        }

        return $this->renderForm('reclamation/update.html.twig', ['formU' => $form]);
    }

}
