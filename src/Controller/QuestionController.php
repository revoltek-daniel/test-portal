<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\User;
use App\Repository\StepRepository;
use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/questions", name="question_start")
     */
    public function index(RequestStack $requestStack, StepRepository $stepRepository, QuestionService $questionService): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $session = $requestStack->getSession();
        $stepCount = $user->getLastStep() ?? 1;
        $step = $stepRepository->findOneBy(['sorting' => $stepCount]);

        if (!$step  instanceof Step) {
            return $this->redirectToRoute('question_sucess');
        }

        $dataObj = [];
        $formBuilder = $this->createFormBuilder($dataObj);

        $form = $questionService->createForm($step, $formBuilder);

        $form->handleRequest($requestStack->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $questionService->handleFormData($step, $data, $entityManager, $user);

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
    public function success(QuestionService $questionService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getResults()->isEmpty()) {
            $result = $questionService->evaluateFormResults($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($result);
            $entityManager->flush();
        }

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
}
