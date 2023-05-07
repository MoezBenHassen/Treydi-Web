<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager , GoogleAuthenticatorInterface $authenticator)
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Set any other fields you need to populate here
            $secret=$authenticator->generateSecret();
            $user->setGoogleAuthenticatorSecret($secret);
            $user->setRoles($form->get('roles')->getData());
            $user->setArchived(false);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');

        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/register/mob', name: 'app_register_m')]
    public function mobileL(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $email = $request->query->get("email");
        if ($email === null) {
            return new Response("Email is required", status: 400);
        }
        $password = $request->query->get("password");
        $roles = $request->query->get("roles");

        $user = new Utilisateur();
        $user->setEmail($email);
        $user->setPassword($passwordEncoder->hashPassword($user, $password));
        $user->setRoles(["ROLE_" . strtoupper($roles)]); // format the role as an array

        try {
            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse("Account is created", status: 200);

        } catch (\Exception $ex) {
            return new Response("Exception: " . $ex->getMessage());
        }
    }


}