<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/statistique', name: 'app_statistique')]
    public function index(): Response
    {
        return $this->render('statistique/index.html.twig', [
            'controller_name' => 'StatistiqueController',
        ]);
    }

    #[Route('/statistique/stat', name: 'app_reclamationstat', methods: ['POST', 'GET'])]
    public function courbe(UtilisateurRepository $utilisateurRepository)
    {
        $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)->compterReclamationsParMois();
        $reclamationst = $this->getDoctrine()->getRepository(Reclamation::class)->compterReclamationsParMoisTraité();
        $mois = [];
        $annees = [];
        $nbReclamations = [];
        $nbReclamationst = [];
        foreach ($reclamations as $reclamation) {
            $mois[] = $reclamation['mois'];
            $annees[] = $reclamation['annee'];
            $nbReclamations[] = $reclamation['nb_reclamations'];
            $nbReclamationst[] = 0;
            foreach ($reclamationst as $r) {
                if ($r['mois'] === $reclamation['mois'] && $r['annee'] === $reclamation['annee']) {
                    $nbReclamationst[count($nbReclamationst) - 1] = $r['nb_reclamations'];
                    break;
                }
            }
        }

        $usersByAge = $utilisateurRepository->countUsersByAge();

        $chartData = [
            'labels' => [],
            'values' => [],
        ];

        foreach ($usersByAge as $userAge) {
            $chartData['labels'][] = $userAge['age'];
            $chartData['values'][] = $userAge['count'];
        }

        $satisfiedCount = $this->getDoctrine()->getRepository(Reponse::class)->compterReponsesatisfait();
        $unsatisfiedCount = $this->getDoctrine()->getRepository(Reponse::class)->compterReponsenonsatisfait();

        $data = [
            'labels' => ['Satisfait', 'Non satisfait'],
            'datasets' => [
                [
                    'data' => [$satisfiedCount, $unsatisfiedCount],
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                ],
            ],
        ];



        return $this->render('statistique/dashboardStat.html.twig', [
            'mois' => json_encode($mois),
            'annees' => json_encode($annees),
            'nbReclamations' => json_encode($nbReclamations),
            'nbReclamationst' => json_encode($nbReclamationst),
            'chartData' => $chartData,
            'data' => json_encode($data),
        ]);
    }






}
