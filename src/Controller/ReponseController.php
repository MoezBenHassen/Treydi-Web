<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReponseAddType;
use App\Form\UpdateReponseType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class ReponseController extends AbstractController
{
    #[Route('/reponse', name: 'app_reponse')]
    public function index(): Response
    {
        return $this->render('reponse/index.html.twig', [
            'controller_name' => 'ReponseController',
        ]);
    }
    #[Route('/reponse/add/{id}', name: 'app_reponseAdd', methods: ['GET', 'POST'])]
    public function add(Request $request, ManagerRegistry $doctrine, Reclamation $reclamation): Response
    {
        $em = $doctrine->getManager();
        $req = new Reponse();
        $req->setArchived(0); // set archived property to 0 (not archived)
        $req->setIdReclamation($reclamation);
        $req->setDateReponse(new DateTime());
        $form = $this->createForm(ReponseAddType::class, $req);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($req);
            $em->flush();
            return $this->redirectToRoute('app_reponseAdd', ['id' => $reclamation->getId()]);
        }
        return $this->renderForm('reponse/add_reponse.html.twig', ['formR' => $form]);
    }

    #[Route('/reponse/list', name: 'app_reponseList', methods: ['GET', 'POST'])]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reponse::class);

        $query = $repository->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->setParameter('archived', 0)
            ->getQuery();
        $listr = $query->getResult();

        return $this->render('reponse/showReponse.html.twig', [
            'controller_name' => 'ReponseListController',
            'listr' => $listr
        ]);
    }

    #[Route('/reponse/delete/{id}', name: 'app_reponseDelete', methods: ['POST'])]
    public function deleteReclamation(Reponse $reponse ,ManagerRegistry $doctrine): Response
    {
        // Set the "archived" attribute to 1 to indicate that the reclamation has been archived
        $reponse->setArchived(1);
        // Save the changes to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $repository = $doctrine->getRepository(Reponse::class);
        $query = $repository->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->setParameter('archived', 0)
            ->getQuery();
        $listr = $query->getResult();

        return $this->render('reponse/showReponse.html.twig', [
            'controller_name' => 'ReponseListController',
            'listr' => $listr
        ]);
    }

    #[Route('/reponse/update/{id}', name: 'app_reponseUpdate')]
    public function update(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $reponse = $em->getRepository(Reponse::class)->find($id);

        if (!$reponse) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        $form = $this->createForm(UpdateReponseType::class, $reponse);
        $form->add('modifier', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_reponseList');
        }

        return $this->renderForm('reponse/updateReponse.html.twig', ['formUr' => $form]);
    }





}
