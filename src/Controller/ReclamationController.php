<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Entity\Utilisateur;
use App\Form\FiltrereclamationType;
use App\Form\ReclamationType;
use App\Form\UpdateformType;
use App\Repository\UtilisateurRepository;
use DateTime;

use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use Twig\Environment;


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
    public function list(Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reclamation::class);

        $form = $this->createForm(FiltrereclamationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $search = $data->getTitreReclamation() ?? null;
            $search2 = $data->getDescriptionReclamation() ?? null;
            $dateCreation = $data->getDateCreation() ? $data->getDateCreation()->format('Y-m-d') : null;
            $etatReclamation = $data->getEtatReclamation() ?? null ;


            $query = $repository->findByTitreEtDescriptionEtDateCreation(false, $search, $search2, $dateCreation, $etatReclamation);
            $list = $query;
            foreach ($list as $reclamation) {
                $userId = $reclamation->getIdUser();
                $user = $doctrine->getRepository(Utilisateur::class)->findOneBy(['id' => $userId]);

                if ($user) {
                    /*$reclamation->userFullName = $livreur->getNom().' '.$livreur->getPrenom();*/
                    $reclamation->setUserFullName($user->getNom().' '.$user->getPrenom());
                    /*$reclamation->avatarUrl = $livreur->getAvatarUrl();*/
                    $reclamation->setAvatarUrl($user->getAvatarUrl());
                }
            }
        } else {

            $list = $repository->findByTitreEtDescriptionEtDateCreation(false);
            foreach ($list as $reclamation) {
                $userId = $reclamation->getIdUser();
                $user = $doctrine->getRepository(Utilisateur::class)->findOneBy(['id' => $userId]);

                if ($user) {
                    /*$reclamation->userFullName = $livreur->getNom().' '.$livreur->getPrenom();*/
                    $reclamation->setUserFullName($user->getNom().' '.$user->getPrenom());
                    /*$reclamation->avatarUrl = $livreur->getAvatarUrl();*/
                    $reclamation->setAvatarUrl($user->getAvatarUrl());
                }
            }
        }



        return $this->render('reclamation/show.html.twig', [
            'controller_name' => 'ReclamationListController',
            'list' => $list,
            'form' => $form->createView(),

        ]);
    }




    #[Route('/reclamation/add', name: 'app_reclamationAdd' , methods: ['POST','GET'])]
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
    #[Route('/reclamation/update/{id}', name: 'app_reclamationUpdate' , methods: ['POST','GET'])]
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

    #[Route('/reclamation/pdf/generate', name: 'app_pdf_generate' , methods: ['POST','GET'])]
    public function generatePdf(Environment $twig, Request $request, ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Reclamation::class);
        $form = $this->createForm(FiltrereclamationType::class);
        $form->handleRequest($request);

        $search = $request->query->get('titre');
        $search2 = $request->query->get('description');
        $dateCreation = $request->query->get('etat');
        $etatReclamation = $request->query->get('date');
            $query = $repository->findByTitreEtDescriptionEtDateCreation(false, $search, $search2, $dateCreation, $etatReclamation);
            $list = $query;



        $html = $this->renderView('reclamation/pdfReclamation.html.twig', [
            'list' => $list,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reclamation.pdf"',
        ]);
    }



    #[Route('/reclamation/pdf/generate/{id}', name: 'app_pdf_generate_id', methods: ['POST', 'GET'])]
    public function generatePdfpressit(Environment $twig, Request $request, ManagerRegistry $doctrine, int $id)
    {
        $repository = $doctrine->getRepository(Reclamation::class);
        $query = $repository->findByidreclamation($id);
        $list = $query;

        $repositoryreponse = $doctrine->getRepository(Reponse::class);
        $queryReponse = $repositoryreponse->findByidreclamation_reponse($id);
        $listReponse = $queryReponse;



        $html = $twig->render('reclamation/pdfReclamation_Reponse.html.twig', [
            'list' => $list,
            'listreponse' => $listReponse,
        ]);

        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="reclamation.pdf"',
            ]
        );

    }









}
