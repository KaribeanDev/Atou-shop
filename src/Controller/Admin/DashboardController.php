<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Article;
use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ATOUMO Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de Bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Commande', 'fa-solid fa-cart-shopping', Order::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user-circle', User::class);
        yield MenuItem::linkToCrud('Adresses', 'fa-solid fa-address-card', Address::class);
        yield MenuItem::linkToCrud('Produits', 'fa-solid fa-seedling', Product::class);
        yield MenuItem::linkToCrud('Catégories', 'fa-solid fa-list-alt', Category::class);
        yield MenuItem::linkToCrud('Transporteurs', 'fa-solid fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('Articles du Blog', 'fa-solid fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Contact', 'fa-solid fa-message', Contact::class);
    }
}
