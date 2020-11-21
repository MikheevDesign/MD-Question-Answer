<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Answer;
use App\Form\AnswerType;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question/{id}", name="app_question",
     * requirements={"id"="\d+"})
     */
    public function index(Question $question, Request $request)
    {
    $entityManager = $this->getDoctrine()->getManager();

    $renderedForm=null;
    
    if ($this->isGranted("IS_AUTHENTICATED_FULLY"))
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $answer->setUser($this->getUser());
            $answer->setQuestion($question);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute("app_question", ['id' => $question->getId()]);
        }
            $renderedForm = $form->createView();
        }

		$question -> setViews($question -> getViews()+1);
		$entityManager = $this -> getDoctrine()->getManager();
		$entityManager -> persist($question);
		$entityManager->flush();
		
        return $this->render('question/index.html.twig', [
            'question' => $question,
            'answerForm' => $form->createView()
        ]);

    }
    
    /**
     * @Route("/question/ask", name="app_question_ask")
     * @IsGranted ("IS_AUTHENTICATED_FULLY")
     */
    public function askQuestion(Request $request)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
		{
				$question->setUser($this->getUser());
				$question->setCreated(new \DateTime());
				
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($question);
				$entityManager->flush();
				return $this->redirectToRoute("app_question", ['id'=>
				$question->getId()
				]);
		}
        return $this->render('question/ask.html.twig', [
			'questionForm' => $form->createView()
        ]);
    }
}
