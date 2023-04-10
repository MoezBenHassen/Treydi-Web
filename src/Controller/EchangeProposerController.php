<?php

namespace App\Controller;

use App\Entity\Echange;
use App\Entity\EchangeProposer;
use App\Entity\Item;
use App\Entity\Utilisateur;
use App\Form\EchangeProposerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use DateTime;

class EchangeProposerController extends AbstractController
{
    #[Route('/echange/proposer', name: 'app_echange_proposer_index')]
    public function index(): Response
    {
        return $this->render('echange_proposer/index.html.twig', [
            'controller_name' => 'EchangeProposerController',
        ]);
    }

    //ECHANGE PROPOSER
    #[Route('/echange/proposer/{id}', name: 'app_echange_proposer')]
    public function proposer(Request $request, ManagerRegistry $doctrine, $id, Security $security): Response
    {
        $current_date = date('Y-m-d');
        $date = new DateTime($current_date);

        $em = $doctrine->getManager();
        $test_user2 = $security->getUser();

        $echange = $em->getRepository(Echange::class)
            ->find($id);
        $echange_proposer = new EchangeProposer();

        //change to echangeproposertype (propably make a new controller)
        $form = $this->createForm(EchangeProposerType::class, $echange_proposer);
        $form->handleRequest($request);

        $user1_items = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange->getIdUser1()]);

        $user2_items = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => NULL, 'id_user' => $test_user2->getId()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $echange_proposer = new EchangeProposer();
            $echange_proposer->setIdEchange($echange);
            $echange_proposer->setArchived(0);
            $echange_proposer->setIdUser($test_user2);
            $echange_proposer->setDateProposer($date);
            $em->persist($echange_proposer);

            $em->flush();

            $items = json_decode(json_encode($request->request->get('items')), true);

            foreach ($items as $id) {
                $idArray = json_decode($id, true);
                foreach ($idArray as $itemId) {
                    $item = $doctrine->getRepository(Item::class)->find($itemId);
                    if ($item) {
                        $item->setIdEchange($echange);
                        $em->persist($item);
                        echo "Item ID: $itemId\n";
                    }
                }
            }
            $em->flush();

            return $this->redirectToRoute('app_echange_list_user');
        }

        return $this->render('echange_proposer/proposer.html.twig', [
            'user1_items' => $user1_items,
            'user2_items' => $user2_items,
            'formA' => $form->createView(),
        ]);
    }

    #[Route('/echange_proposer/user/afficher/{id}', name: 'app_echangeproposer_user_afficher')]
    public function afficherEchangeUser(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();

        $echange_proposer = $em->getRepository(EchangeProposer::class)
            ->find($id);

        $echange = $em->getRepository(Echange::class)
            ->find($echange_proposer->getIdEchange());

        $user1 = $em->getRepository(Utilisateur::class)
            ->find($echange->getIdUser1());

        $user2 = $em->getRepository(Utilisateur::class)
            ->find($echange_proposer->getIdUser());

        $user1_items = $em
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange->getIdUser1()]);

        $user2_items = $em
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange_proposer->getIdUser()]);


        return $this->render('echange_proposer/afficher.html.twig', [
            'user1_items' => $user1_items,
            'user2_items' => $user2_items,
            'user1' => $user1,
            'user2' => $user2,
            'echange_proposer' => $echange_proposer,
        ]);
    }

    #[Route('/echange_proposer/user/accepter/{id}', name: 'app_echangeproposer_user_accepter')]
    public function accepterEchangeUser(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();

        $echange_proposer = $em->getRepository(EchangeProposer::class)
            ->find($id);

        $echange = $em->getRepository(Echange::class)
            ->find($echange_proposer->getIdEchange());

        $user1 = $em->getRepository(Utilisateur::class)
            ->find($echange->getIdUser1());

        $user2 = $em->getRepository(Utilisateur::class)
            ->find($echange_proposer->getIdUser());

        $echange->setIdUser2($user2);

        $other_echange_proposers = $em
            ->getRepository(EchangeProposer::class)
            ->findBy(['id_echange' => $echange->getId()]);

        foreach ($other_echange_proposers as $other_echange_proposer) {
            if ($other_echange_proposer->getId() !== $echange_proposer->getId()) {
                $other_items = $em
                    ->getRepository(Item::class)
                    ->findBy(['id_echange' => $echange->getId(), 'id_user' => $other_echange_proposer->getIdUser()]);

                foreach ($other_items as $other_item) {
                    $other_item->setIdEchange(null);
                    $em->persist($other_item);
                }
            }
        }

        $em->persist($echange);
        $em->flush();

        return $this->redirectToRoute('app_echange_list_user');
    }


}


