<?php

namespace App\Controller;

use App\Form\EditUserFormType;
use App\Form\EditUserPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserHomeController extends AbstractController
{
    #[Route('/HomeTr', name: 'app_home_user')]
    public function index(): Response
    {
        return $this->render('user_home/home_user.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/HomeTr/edit', name: 'app_edit_user')]
    public function edit(Request $request ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_edit_user');
        }

        return $this->render('user_home/edituser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/HomeTr/edit/pass', name: 'app_edit_pass')]
    public function editpass(Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $passwordEncoder->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home_user');
        }

        return $this->render('user_home/editpass.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/HomeTr/show', name: 'app_show_user', methods: ['GET'])]
    public function showConnectedUser(Request $request): Response
    {
        $user = $this->getUser();
        // Set the avatar URL property based on the user's ID and the image filenam
        return $this->render('user_home/show.html.twig', [
            'user' => $user,

        ]);
    }

}
