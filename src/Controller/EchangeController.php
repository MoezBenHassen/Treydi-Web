<?php

namespace App\Controller;

use App\Entity\Echange;
use App\Entity\Item;
use App\Form\EchangeType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;




class EchangeController extends AbstractController
{
    #[Route('/echange', name: 'app_echange')]
    public function index(): Response
    {
        return $this->render('echange/index.html.twig', [
            'controller_name' => 'EchangeController',
        ]);
    }

    //ECHANGE
    #[Route('/echange/creer', name: 'app_echange_creer')]
    public function creer(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $em = $doctrine->getManager();
        $test_user1 = $security->getUser();

        $current_date = date('Y-m-d');
        $date = new DateTime($current_date);

        $user_items = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => NULL, 'id_user' => $test_user1->getId()]);

        $echange = new Echange();
        $form = $this->createForm(EchangeType::class, $echange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $echange->setIdUser1($test_user1);
            $echange->setArchived(0);
            $echange->setLivEtat("Non_Accepter");
            $echange->setDateEchange($date);
            $em->persist($echange);
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

            return $this->redirectToRoute('app_echangeList');
        }

        return $this->render('echange/creer.html.twig', [
            'formA' => $form->createView(),
            'user_items' => $user_items,
        ]);
    }

    #[Route('/echange/list', name: 'app_echangeList')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Echange::class);
        $list = $repository->findBy(['archived' => false]);

        return $this->render('echange/list.html.twig', [
            'controller_name' => 'EchangeListController',
            'list' => $list
        ]);
    }

    #[Route('/echange/delete/{id}', name: 'app_echangeDelete')]
    public function remove(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Echange::class);
        $echange = $repository->find($id);


        $items = $echange->getItems();
        $items->initialize();

        foreach ($items as $item) {
            $item->setIdEchange(null);
            $em->persist($item);
        }

        $echange->setArchived(true);
        $em->persist($echange);

        $em->flush();

        return $this->redirectToRoute('app_echangeList');
    }

    #[Route('/echange/afficher/{id}', name: 'app_echange_afficher')]
    public function afficher(ManagerRegistry $doctrine, $id): Response
    {
        $echange = $doctrine->getRepository(Echange::class)
            ->find($id);

        $user1_items = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange->getIdUser1()]);

        $user2_items = $doctrine
            ->getRepository(Item::class)
            ->findBy(['id_echange' => $echange->getId(), 'id_user' => $echange->getIdUser2()]);

        return $this->render('echange/afficher.html.twig', [
            'user1_items' => $user1_items,
            'user2_items' => $user2_items,
        ]);
    }
}
