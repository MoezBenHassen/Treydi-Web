<?php

namespace App\Controller;

use App\Form\GPTType;
use App\Service\GptClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GptToolController extends AbstractController
{
    #[Route('/gpt/tool', name: 'app_gpt_tool', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        GptClient $gptClient
    ): Response
    {
        $answer = null;
        $form = $this->createForm(GPTType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $prompt = $form->get('prompt')->getData();
            // do something with the data
            $answer = $gptClient->generate($prompt);
            /*return $this->redirectToRoute('app_gpt_tool');*/
        }
        return $this->render('gpt_tool/index.html.twig', [
            'form' => $form->createView(),
            'answer' => $answer ?? null,
        ]);
    }
}
