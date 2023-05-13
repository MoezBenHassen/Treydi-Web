<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\FiltrereclamationType;
use App\Form\ReclamationType;
use App\Form\UpdateformType;
use App\Repository\ReclamationRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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
        $req->setIdUser($this->getUser());
        $form = $this->createForm(ReclamationType::class, $req);
        $form->add('ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($req);
            $em->flush();
            return $this->redirectToRoute('app_reclamationUserList');
        }
        return $this->renderForm('reclamation_user/addReclamationUser.html.twig', array('formA' => $form));
    }
     #[Route('/reclamation/addUserm', name: 'app_reclamationUserAqqddm',methods: ['POST'])]
    public function ajouterReclamationMobile(Request $request){
        $titre_reclamation = $request->query->get('titre_reclamation');
        $description_reclamation = $request->query->get('description_reclamation');
         $id_user= $request->query->get('id_user');

        if ($titre_reclamation != null && $description_reclamation != null) {
            $reclamation = new Reclamation();
            $em= $this->getDoctrine()->getManager();
            $date_creation   = new DateTime();
            $etat_reclamation = "en cours";
            $archived = false;
            $reclamation->setIdUser($id_user);
            $reclamation->setTitreReclamation($titre_reclamation);
            $reclamation->setDescriptionReclamation($description_reclamation);
            $reclamation->setDateCreation($date_creation);
            $reclamation->setEtatReclamation($etat_reclamation);
            $reclamation->setArchived($archived);
            $em->persist($reclamation);
            $em->flush();
            $normalizers = [new ObjectNormalizer()];
            $encoders = [new JsonEncoder()];
            $serializer = new Serializer($normalizers, $encoders);
            $formatted = $serializer->serialize($reclamation, 'json');
            return new JsonResponse($formatted);
        } else {
            return new JsonResponse("Invalid input data");
        }
    }





    #[Route('/reclamation/updateUserm', name: 'app_reclamationUserAddm', methods: ['POST'])]
    public function updateReclamationMobile(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($request->request->get('id'));

        if (!$reclamation) {
            return new JsonResponse(['error' => 'Reclamation not found.'], Response::HTTP_NOT_FOUND);
        }

        $reclamation->setTitreReclamation($request->request->get('titre_reclamation'));
        $reclamation->setDescriptionReclamation($request->request->get('description_reclamation'));

        $entityManager->persist($reclamation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Reclamation updated successfully.']);
    }

    #[Route('/reclamation/deletem', name: 'app_reclamationUserdeletem', methods: ['POST'])]
    public function deleteReclamationMobile(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($request->request->get('id'));

        if (!$reclamation) {
            return new JsonResponse(['error' => 'Reclamation not found.'], Response::HTTP_NOT_FOUND);
        }

        $reclamation->setArchived(true);

        $entityManager->persist($reclamation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Reclamation deleted successfully.']);
    }
  #[Route('/reclamation/listm', name: 'app_reclamationList_m', methods: ['POST', 'GET'])]
    public function mobileL(ReclamationRepository $repository,ManagerRegistry $doctrine,Request $request): JsonResponse
    {
        $list = $repository->findBy(['archived' => false,   'id_user' => $request->query->get('id_user')]);

        $reclamationArray = array_map(function (Reclamation $rec) {
            return [
                'id' => $rec->getId(),
                'titre_reclamation' => $rec->getTitreReclamation(),
                'description_reclamation' => $rec->getDescriptionReclamation(),
                'etat_reclamation' => $rec->getEtatReclamation(),
                'date_creation' => $rec->getDateCreation(),
                'date_cloture' => $rec->getDateCloture(),
                'archived' => $rec->isArchived(),

            ];
        }, $list);

        // create a JSON response containing the items array
        return new JsonResponse(['recs' => $reclamationArray]);
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

            $userId = $this->getUser()->getId();
            $query = $repository->findByTitreEtDescriptionEtDateCreationUser(false, $search, $search2, $dateCreation, $etatReclamation, $userId);
            $list = $query;
        } else {
            $userId = $this->getUser()->getId();
            $list = $repository->findByTitreEtDescriptionEtDateCreationUser(false, null, null, null, null, $userId);
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

        $form = $this->createForm(FiltrereclamationType::class);
        $form->handleRequest($request);

        $search = $request->query->get('titre');
        $search2 = $request->query->get('description');
        $dateCreation = $request->query->get('etat');
        $etatReclamation = $request->query->get('date');

        $userId = $this->getUser()->getId();
        $query = $repository->findByTitreEtDescriptionEtDateCreationUser(false, $search, $search2, $dateCreation, $etatReclamation, $userId);
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
