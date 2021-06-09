<?php

namespace App\Controller\Admin;

use App\Repository\ResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{
    /**
     * @Route("/admin/results", name="admin_results")
     * @return Response
     */
    public function indexAction(ResultRepository $resultRepository): Response
    {
        $results = $resultRepository->findBy([], ['createdAt' => 'ASC']);

        return $this->render(
            'admin/result/index.html.twig',
            [
                'results' => $results,
            ]
        );
    }
}
