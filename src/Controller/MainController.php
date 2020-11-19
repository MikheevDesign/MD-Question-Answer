<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\QuestionRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(QuestionRepository $questionRepository): Response
    {
		$question = $questionRepository->findBy([],['created' => 'DESC']);
        return $this->render('main/index.html.twig', [
            'question' => $question,
        ]);
    }
}
