<?php

namespace App\Controller;

use App\Form\EditUserFormType;
use App\Form\EditUserPasswordType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


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
    #[Route("/updateProfileMobile", name: "updateProfileMobile",methods: ['GET', 'POST'])]
    public function updateProfileMobile(Request $request, NormalizerInterface $normalizer, ManagerRegistry $doctrine, UtilisateurRepository $repo)
    {
        $id = $request->query->get("id");
        $user = $repo->find($id);

        $password = $request->query->get("password");
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $adresse = $request->query->get("adresse");

        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setAdresse($adresse);
        $user->setPassword(sha1($password));

        $email = $request->query->get("email");
        if ($email !== null) {
            $user->setEmail($email);
        }

        $manager = $doctrine->getManager();
        $manager->persist($user);
        $manager->flush();

        return new JsonResponse($normalizer->normalize($user));
    }
    }
