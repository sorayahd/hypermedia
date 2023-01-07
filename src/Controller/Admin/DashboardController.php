<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\CategorieArticle;
use App\Entity\Genre;
use App\Entity\User;
use App\Entity\Transporteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();
        return $this->render('admin/dashboard.html.twig');
     
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('WEAR IT');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Articles', 'fas fa-shopping-cart', Article::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', CategorieArticle::class);
        yield MenuItem::linkToCrud('Genres', 'fas fa-list', Genre::class);
        yield MenuItem::linkToCrud('utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Transporteur', 'fas fa-user', Transporteur::class);
    }
}
