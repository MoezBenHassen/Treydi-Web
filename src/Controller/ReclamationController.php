<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\UpdateformType;
use DateTime;

use Dompdf\Dompdf;
use mPDF;
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

        $search = $request->query->get('search');
        $dateCreation = $request->query->get('dateCreation');
        $query = $repository->findByTitreEtDescriptionEtDateCreation(false, $search, $dateCreation);
        $list = $query;

        return $this->render('reclamation/show.html.twig', [
            'controller_name' => 'ReclamationListController',
            'list' => $list,
            'search' => $search,
            'dateCreation' => $dateCreation,
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
    #[Route('/reclamation/stat', name: 'app_reclamationstat' , methods: ['POST','GET'])]
    public function courbe()
    {
        $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)->compterReclamationsParMois();

        $mois = [];
        $annees = [];
        $nbReclamations = [];

        foreach ($reclamations as $reclamation) {
            $mois[] = $reclamation['mois'];
            $annees[] = $reclamation['annee'];
            $nbReclamations[] = $reclamation['nb_reclamations'];
        }

        return $this->render('reclamation/stat.html.twig', [
            'mois' => json_encode($mois),
            'annees' => json_encode($annees),
            'nbReclamations' => json_encode($nbReclamations),
        ]);
    }
    #[Route('/reclamation/pdf/generate', name: 'app_pdf_generate' , methods: ['POST','GET'])]
        public function generatePdf(Environment $twig,Request $request, ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Reclamation::class);

        $search = $request->query->get('search');
        $dateCreation = $request->query->get('dateCreation');
        $query = $repository->findByTitreEtDescriptionEtDateCreation(false, $search, $dateCreation);
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



}
