<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\FiltrereclamationType;
use App\Form\ReclamationType;
use App\Form\UpdateformType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

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
        } else {
            $list = $repository->findByTitreEtDescriptionEtDateCreation(false);
        }

        return $this->render('reclamation_user/showReclamationUser.html.twig', [
            'controller_name' => 'ReclamationListController',
            'list' => $list,
            'form' => $form->createView(),


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

    #[Route('/reclamation/pdf/generateUser', name: 'app_pdf_generateUser' , methods: ['POST','GET'])]
    public function generatePdf(Environment $twig,Request $request, ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Reclamation::class);

        $search = $request->query->get('search');
        $dateCreation = $request->query->get('dateCreation');
        $query = $repository->findByTitreEtDescriptionEtDateCreationUser(false, $search, $dateCreation);
        $list = $query;

        $html = $this->renderView('reclamation/pdfReclamation.html.twig', [
            'list' => $list,

        ]);

        // Instancier Dompdf
        $dompdf = new Dompdf();

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Personnaliser les options de Dompdf
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Envoyer le PDF en tant que réponse HTTP
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reclamation.pdf"',
        ]);
    }
    #[Route('/reclamation/pdf/generateUser/{id}', name: 'app_pdf_generate_id_user', methods: ['POST', 'GET'])]
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
