<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\UserAnswer;
use App\Repository\StepRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/questions", name="question_start")
     */
    public function index(Request $request, StepRepository $stepRepository): Response
    {
        $step = 1;
        $step = $stepRepository->findOneBy(['sorting' => $step]);
        $form = $this->createStepForm($step);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $items = [];
            foreach ($step->getQuestions() as $question) {
                $answers = [];
                foreach ($question->getAnswers() as $answer) {
                    $answers[$answer->getId()] = $answer;
                }
                $items[$question->getId()] = [
                    'question' => $question,
                    'amswers' => $answers,
                ];
            }

            $entityManager = $this->getDoctrine()->getManager();

            foreach ($data as $id => $answerValue) {
                if ($answerValue === null) {
                    continue;
                }

                $answer = new UserAnswer();

                $answer->setQuestion($items[$id]['question']);
                $answer->setAnswer($answerValue);
                $answer->setStep($step);
                $answer->setUser($this->getUser());

                $entityManager->persist($answer);
            }
            $entityManager->flush();
        }

        return $this->render(
            'question/index.html.twig',
            [
                'step' => $step,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param Step $step
     *
     * @return FormInterface
     */
    protected function createStepForm(Step $step): FormInterface
    {
        $dataObj = [];
        $formBuilder = $this->createFormBuilder($dataObj);

        foreach ($step->getQuestions() as $question) {
            $items = [];
            foreach ($question->getAnswers() as $answer) {
                $items[$answer->getId()] = $answer->getTitle();
            }

            $items = \array_flip($items);

            $formBuilder->add(
                $question->getId(),
                ChoiceType::class,
                [
                    'required' => false,
                    'label' => $question->getTitle(),
                    'choices' => $items,
                    'expanded' => true,
                    'multiple' => false,
                ]
            );
        }

        $formBuilder->add('submit', SubmitType::class);

        return $formBuilder->getForm();
    }
}
