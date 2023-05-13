<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/2fa', name: '2fa_login')]
    public function Check2fa(GoogleAuthenticatorInterface $authenticator, TokenStorageInterface $storage)
    {
        $code= $authenticator->getQRContent($storage->getToken()->getUser());
        $qrCode = "https://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=" . urlencode($code);
        return $this->render('security/2fa_form.html.twig',[
            'qrCode' => $qrCode
        ]);
    }
    #[Route(path: '/login/mob', name: 'app_login_mob', methods: ['GET'])]
    #[Route(path: '/login/mob', name: 'app_login_mob', methods: ['GET'])]
    public function loginMob(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');

        // Check if the email and password are valid
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy([
            'email' => $email,
        ]);

        if ($utilisateur === null) {
            return new JsonResponse(['status' => 'failed'], Response::HTTP_UNAUTHORIZED);
        }

        $isValidPassword = $passwordHasher->isPasswordValid($utilisateur, $password);

        if (!$isValidPassword) {
            return new JsonResponse(['status' => 'failed'], Response::HTTP_UNAUTHORIZED);
        }

        // Create a session for the user
        $session = $request->getSession();
        $session->set('user_id', $utilisateur->getId());
        $session->set('score', $utilisateur->getScore());
        $session->set('nom', $utilisateur->getId());
        $session->set('prenom', $utilisateur->getPrenom());
        $session->set('adresse', $utilisateur->getAdresse());
        $session->set('score', $utilisateur->getScore());

        return new JsonResponse(['id' => $utilisateur->getId(),'password' => $utilisateur->getPassword(), 'email' => $utilisateur->getEmail(),'nom' => $utilisateur->getNom(),'prenom' => $utilisateur->getPrenom(),'adresse' => $utilisateur->getAdresse(),'score' => $utilisateur->getScore()]);
    }

}