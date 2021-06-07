<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\User;
use App\Entity\UserAnswer;
use App\Repository\StepRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/questions", name="question_start")
     */
    public function index(RequestStack $requestStack, StepRepository $stepRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $session = $requestStack->getSession();
        $stepCount = $user->getLastStep() ?? 1;
        $step = $stepRepository->findOneBy(['sorting' => $stepCount]);

        if (!$step  instanceof Step) {
            return $this->redirectToRoute('question_sucess');
        }

        $form = $this->createStepForm($step);

        $form->handleRequest($requestStack->getCurrentRequest());
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

            $nextStep = $stepCount + 1;
            $user->setLastStep($nextStep);
            $entityManager->persist($user);
            $entityManager->flush();
            $session->set('stepTime', 0);
            return $this->redirectToRoute('question_start');
        }

        $durationInSeconds = $step->getTime() * 60;
        $timeLeft = ($durationInSeconds - $session->get('stepTime', 0));

        $minutes = floor($timeLeft / 60 % 60);
        $seconds = $timeLeft - $minutes * 60;

        $timeFormated = $minutes . ':' . \str_pad($seconds, 2, 0);

        return $this->render(
            'question/index.html.twig',
            [
                'step' => $step,
                'form' => $form->createView(),
                'timeLeft' => $timeFormated,
                'timeLeftMinutes' => $timeLeft / 60,
            ]
        );
    }

    /**
     * @Route("/questions/success", name="question_sucess")
     */
    public function success(): Response
    {
        return $this->render(
            'question/success.html.twig'
        );
    }

    /**
     * @Route("/questions/ajaxUpdate", name="question_ajax_update")
     */
    public function ajaxUpdate(RequestStack $requestStack): JsonResponse
    {
        $session = $requestStack->getSession();
        $timePassed = $session->get('stepTime', 0);
        $timePassed += 5;
        $session->set('stepTime', $timePassed);
        return $this->json(['success' => true, 'timePassed' => $timePassed]);
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
