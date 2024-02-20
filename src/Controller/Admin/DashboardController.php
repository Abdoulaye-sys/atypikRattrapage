<?php

namespace App\Controller\Admin;

use App\Entity\Hebergement;
use App\Entity\Payment;
use App\Entity\Property;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/my_dashboard.html.twig');
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Atypikhouse')
            ->setFaviconPath('../../../public/images/logo/logo-png.png')
            ->setTitle('Atypikhouse|Admin');
    }


    public function configureAssets(): Assets
    {
        return Assets::new()
        ->addCssFile('/public/bundles/easyadmin/app.a358ddd1.css')
        ->addCssFile('/public/bundles/easyadmin/app.a358ddd1.rtl.css')
        ->addJsFile('/public/bundles/easyadmin/app.c3e41b70.js')
        ->addJsFile('/public/bundles/easyadmin/form.6e84c31d.js')
        ->addJsFile('/public/bundles/easyadmin/page-layout.3347892e.js')
        ->addJsFile('/public/bundles/easyadmin/page-color-scheme.e0316f30.js')
        ->addJsFile('/public/bundles/easyadmin/field-boolean.38cf4737.js')
        ->addCssFile('/public/bundles/easyadmin/field-code-editor.78ccdb00.css')
        ->addCssFile('/public/bundles/easyadmin/field-code-editor.78ccdb00.rtl.css')
        ->addJsFile('/public/bundles/easyadmin/field-code-editor.5893dd28.js')
        ->addJsFile('/public/bundles/easyadmin/field-collection.8ea41328.js')
        ->addJsFile('/public/bundles/easyadmin/field-file-upload.a3a92842.js')
        ->addJsFile('/public/bundles/easyadmin/field-image.19d51d41.js')
        ->addJsFile('/public/bundles/easyadmin/field-slug.051e4dcd.js')
        ->addJsFile('/public/bundles/easyadmin/field-textarea.268f9a4d.js')
        ->addCssFile('/public/bundles/easyadmin/field-text-editor.7f2b8426.css')
        ->addCssFile('/public/bundles/easyadmin/field-text-editor.7f2b8426.rtl.css')
        ->addJsFile('/public/bundles/easyadmin/field-text-editor.833157ee.js')
        ->addJsFile('/public/bundles/easyadmin/login.7259f5de.js');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Blog'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Logement', 'fa fa-home', Property::class),
            MenuItem::linkToCrud('Hebergement', 'fa fa-calendar', Hebergement::class),
            MenuItem::linkToCrud('Payment', 'fa fa-money', Payment::class),
            MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        ];
    }}
