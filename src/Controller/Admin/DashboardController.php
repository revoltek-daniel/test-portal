<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Entity\Result;
use App\Entity\Step;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Test');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Teilnehmer');
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
     //   yield MenuItem::linktoRoute('Auswertung', 'fas fa-chart-bar', 'admin_results');
        yield MenuItem::linkToCrud('Auswertung', 'fas fa-chart-bar', Result::class);
        yield MenuItem::section('Konfiguration');
        yield MenuItem::linkToCrud('Schritte', 'fas fa-list', Step::class);
        yield MenuItem::linkToCrud('Fragen', 'fas fa-list', Question::class);
    }
}
