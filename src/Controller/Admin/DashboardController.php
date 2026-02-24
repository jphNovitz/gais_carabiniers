<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Club;
use App\Entity\FacebookEvent;
use App\Controller\Admin\Club\ClubCrudController;
use App\Controller\Admin\FacebookEventCrudController;
use App\Controller\Admin\Post\PostCrudController;
use App\Controller\Admin\Category\CategoryCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_post_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gai Carabiniers');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkTo('Club', 'fas fa-building', fn(AdminUrlGenerator $g) => $g->setController(ClubCrudController::class)->generateUrl());
        yield MenuItem::linkTo('Facebook Events', 'fab fa-facebook', fn(AdminUrlGenerator $g) => $g->setController(FacebookEventCrudController::class)->generateUrl());
        yield MenuItem::submenu('Posts', 'fas fa-blog')
            ->setSubItems([
                MenuItem::linkTo('Post', 'fas fa-blog', fn(AdminUrlGenerator $g) => $g->setController(PostCrudController::class)->generateUrl()),
                MenuItem::linkTo('Category', 'fas fa-blog', fn(AdminUrlGenerator $g) => $g->setController(CategoryCrudController::class)->generateUrl()),
            ]);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
