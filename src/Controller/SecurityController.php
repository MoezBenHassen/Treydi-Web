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
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $session->set('nom', $utilisateur->getId());
        $session->set('prenom', $utilisateur->getPrenom());
        $session->set('adresse', $utilisateur->getAdresse());
        $session->set('score', $utilisateur->getScore());

        return new JsonResponse(['id' => $utilisateur->getId(),'password' => $utilisateur->getPassword(), 'email' => $utilisateur->getEmail(),'nom' => $utilisateur->getNom(),'prenom' => $utilisateur->getPrenom(),'adresse' => $utilisateur->getAdresse(),'score' => $utilisateur->getScore()]);
    }

    #[Route(path: '/login/getpasswordbyemail', name: 'app_password_mob', methods: ['GET'])]
    public function getPasswordByEmail(Request $request){
        $email =$request->get('email');
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy([
            'email' => $email,
        ]);
        if($utilisateur){
            $password= $utilisateur->getPassword();
            $serializer = new Serializer ([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($password);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");

    }
    #[Route('/updateUserm', name: 'app_updateUser_mobile', methods: ['POST'])]
    public function updateUserMobile(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Utilisateur::class)->find($request->request->get('id'));

        if (!$user) {
            return new JsonResponse(['error' => 'user not found.'], Response::HTTP_NOT_FOUND);
        }

        $user->setNom($request->request->get('nom'));
        $user->setPrenom($request->request->get('prenom'));
        $user->setAdresse($request->request->get('adresse'));
        $user->setPassword($request->request->get('password'));


        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'user updated successfully.']);
    }
    #[Route('/showUserm/{id}', name: 'app_showUser_mobile', methods: ['GET'])]
    public function showUserMobile($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Utilisateur::class)->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'user not found.'], Response::HTTP_NOT_FOUND);
        }

        $userDetails = [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'adresse' => $user->getAdresse(),
            'password' => $user->getPassword(),
        ];

        return new JsonResponse($userDetails);
    }



}
