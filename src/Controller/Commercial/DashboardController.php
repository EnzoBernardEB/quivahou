<?php

namespace App\Controller\Commercial;

use App\Entity\User;
use App\Form\SearchType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/commercial', name: 'commercial')]
    public function index(): Response
    {
        $allUser = $this->getDoctrine()->getRepository(User::class)->findAll();
        $userCollaborateur=[];
        $userCommercial=[];
        $userCandidat=[];
        foreach ($allUser as $user) {
            $userRole=$user->getRoles();
            if (in_array('ROLE_COLLABORATEUR',$userRole)) {
                $userCollaborateur[]=$user;
            } elseif (in_array('ROLE_COMMERCIAL',$userRole)) {
                $userCommercial[]=$user;
            } elseif (in_array('ROLE_CANDIDAT',$userRole)) {
                $userCandidat[]=$user;
            }
        }
        $collab=count($userCollaborateur);
        $commercial=count($userCommercial);
        $candidat=count($userCandidat);


        return $this->render('commercial/dashboard.html.twig', [
            'colab' => $collab,
            'commercial'=>$commercial,
            'candidat'=>$candidat,
            'allColab' => $userCollaborateur,
            'allCommercial'=>$userCommercial,
            'allCandidat'=>$userCandidat,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ECF Symfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-address-card', User::class);
    }
}
