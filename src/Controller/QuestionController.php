<?php

namespace App\Controller;

use App\Repository\StepRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/questions", name="question_start")
     */
    public function index(StepRepository $stepRepository): Response
    {
        $step = 1;
        $step = $stepRepository->findOneBy(['sorting' => $step]);

        return $this->render('question/index.html.twig', [
            'step' => $step,
        ]);
    }
}
