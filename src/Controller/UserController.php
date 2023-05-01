<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UserType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
#[Route('/livreur')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UtilisateurRepository $userRepository): Response
    {
        $users = $userRepository->findBy(['archived' => 0]);

        return $this->render('livreur/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurRepository $userRepository): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livreur/new.html.twig', [
            'livreur' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(Utilisateur $user): Response
    {
        return $this->render('livreur/show.html.twig', [
            'livreur' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $user, UtilisateurRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livreur/edit.html.twig', [
            'livreur' => $user,
            'form' => $form,
        ]);
    }

    #[Route('livreur/delete/{id}', name: 'app_user_delete', methods: ['POST','GET'])]
    public function delete(Utilisateur $user, ManagerRegistry $doctrine): Response
    {
        $user->setArchived(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('app_user_index');
    }
}
