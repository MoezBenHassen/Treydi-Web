<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Form\AuthorsType;
use App\Repository\AuthorsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/authors')]
class AuthorsController extends AbstractController
{
    #[Route('/', name: 'app_authors_index', methods: ['GET'])]
    public function index(AuthorsRepository $authorsRepository): Response
    {
        /*findBy archived falseµ/*/
        $authors=$authorsRepository->findBy(['archived' => false]);
        dump($authors);
        return $this->render('authors/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/new', name: 'app_authors_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AuthorsRepository $authorsRepository): Response
    {
        $author = new Authors();
        $form = $this->createForm(AuthorsType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorsRepository->save($author, true);

            return $this->redirectToRoute('app_authors_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('authors/new.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_authors_show', methods: ['GET'])]
    public function show(Authors $author): Response
    {
        return $this->render('authors/show.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_authors_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Authors $author, AuthorsRepository $authorsRepository): Response
    {
        $form = $this->createForm(AuthorsType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorsRepository->save($author, true);

            return $this->redirectToRoute('app_authors_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('authors/edit.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_authors_delete', methods: ['POST'])]
    public function delete(Request $request, Authors $author, AuthorsRepository $authorsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $authorsRepository->remove($author, true);
        }

        return $this->redirectToRoute('app_authors_index', [], Response::HTTP_SEE_OTHER);
    }
}
