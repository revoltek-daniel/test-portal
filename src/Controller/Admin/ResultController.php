<?php

namespace App\Controller\Admin;

use App\Entity\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends AbstractController
{
    /**
     * @Route("/admin/results/{id}", name="admin_results")
     * @return Response
     */
    public function indexAction(Result $result): Response
    {
        return $this->render(
            'admin/result/index.html.twig',
            [
                'result' => $result,
            ]
        );
    }
}
