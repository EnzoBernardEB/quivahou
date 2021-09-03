<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Entreprise;
use App\Entity\TypeMission;
use App\Entity\User;
use App\Form\ReferentType;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin'), IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $accounts = $this->getDoctrine()->getRepository(User::class)->count([]);
        $accountsPendingAccept = $this->getDoctrine()->getRepository(User::class)->findBy(['isAccepted'=>false]);
        $userRole=$this->getUser()->getRoles();
        if (in_array('ROLE_COMMERCIAL',$userRole)) {
            $isCommercial=true;
        } else {
            $isCommercial=false;
        }

        $userNoReferent=$this->getDoctrine()->getRepository(User::class)->isRef();
        $countUserNoRef=count($userNoReferent);


        return $this->render('Admin/dashboard.html.twig', [
            'accounts' => $accounts,
            'accountsPending'=>count($accountsPendingAccept),
            'isCommercial'=>$isCommercial,
            'countNoRef'=>$countUserNoRef,
            'noRef'=>$userNoReferent,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ecf Symfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-address-card', User::class);
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-list-alt', Categorie::class);
        yield MenuItem::linkToCrud('Compétence', 'fas fa-clipboard-list ', Competence::class);
        yield MenuItem::linkToCrud('Type experience', 'fas fa-align-justify ', TypeMission::class);
        yield MenuItem::linkToCrud('Entreprise', 'fas fa-building', Entreprise::class);
    }

}
