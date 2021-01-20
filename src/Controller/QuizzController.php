<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Quizz;

/**
 * @Route("/quizz",name="quizz_")
 */
class QuizzController extends AbstractController
{
    /**
     * @Route("/random", name="quizz")
     */
    public function index(Quizz $quizz): Response
    {

        $fromquizz = $quizz->getRandomQuestion();

        return $this->render('quizz/index.html.twig', [
            'question' => $fromquizz['question'],
            'reponse' => $fromquizz['reponse']
        ]);
    }
    
    /**
     * @Route("/all",name="allquestions")
     */
    public function allQuestion(Quizz $quizz): Response
    {

        $fromquizz = $quizz->getAllQuestions();
        var_dump($fromquizz);

        return $this->render('quizz/all.html.twig', [
            'all' => $fromquizz
        ]);
    }
}
