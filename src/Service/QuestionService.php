<?php

namespace App\Service;

use App\Entity\Step;
use App\Entity\UserAnswer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionService
{
    public function handleFormData(Step $step, array $data, EntityManagerInterface $entityManager, UserInterface $user)
    {
        switch ($step->getType()) {
            case Step::TYPE_QUESTION:
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

                foreach ($data as $id => $answerValue) {
                    if ($answerValue === null) {
                        continue;
                    }

                    $answer = new UserAnswer();

                    $answer->setQuestion($items[$id]['question']);
                    $answer->setAnswer($answerValue);
                    $answer->setStep($step);
                    $answer->setUser($user);

                    $entityManager->persist($answer);
                }
                break;
            case Step::TYPE_TEXT:
                $answer = new UserAnswer();

               // $answer->setQuestion();
                $answer->setAnswer((string)$data['text']);
                $answer->setStep($step);
                $answer->setUser($user);

                $entityManager->persist($answer);
                break;
        }
    }

    /**
     * @param Step                 $step
     * @param FormBuilderInterface $formBuilder
     *
     * @return FormInterface
     */
    public function createForm(Step $step, FormBuilderInterface $formBuilder): FormInterface
    {
        switch ($step->getType()) {
            case Step::TYPE_QUESTION:
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
                break;
            case Step::TYPE_TEXT:
                $formBuilder->add(
                    'text',
                    TextareaType::class,
                    [
                        'required' => false,
                        'trim' => true,
                    ]
                );
                break;
        }

        $formBuilder->add('submit', SubmitType::class);

        return $formBuilder->getForm();
    }
}
