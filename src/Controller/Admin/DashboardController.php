<?php

namespace App\Controller\Admin;


use App\Controller\Admin\Meeting\MeetingCrudController;
use App\Controller\Admin\Club\ClubCrudController;
use App\Controller\Admin\Faq\FaqCrudController;
use App\Controller\Admin\Post\PostCrudController;
use App\Controller\Admin\Post\CategoryCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\FacebookEventCrudController;

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
        yield MenuItem::linkTo(MemberCrudController::class, 'Membres', 'fa fa-users');
        yield MenuItem::linkTo(ClubCrudController::class, 'Le Club', 'fa fa-building');
        yield MenuItem::linkTo(FacebookEventCrudController::class, 'Evènements Facebook', 'fab fa-facebook');
        yield MenuItem::submenu('Posts', 'fas fa-blog')
            ->setSubItems([
                MenuItem::linkTo( PostCrudController::class, 'Post', 'fas fa-blog'),
                MenuItem::linkTo(CategoryCrudController::class, 'Category', 'fas fa-blog'),
            ]);
        yield MenuItem::linkTo(FaqCrudController::class, 'FAQ', 'fas fa-question-circle');
        yield MenuItem::linkTo(MeetingCrudController::class, 'nav.meetings', 'fas fa-calendar');
    }

}
