<?php

namespace App\Controller\Commercial;

use App\Entity\MissionEnCours;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/commercial', name: 'commercial'),IsGranted('ROLE_COMMERCIAL')]
    public function index(): Response
    {
        $allUser = $this->getDoctrine()->getRepository(User::class)->findAll();
        $userCollaborateur=[];
        $userCommercial=[];
        $userAvailable= $this->getDoctrine()->getRepository(User::class)->isAvailable(1);
        foreach ($allUser as $user) {
            $userRole=$user->getRoles();
            if (in_array('ROLE_COLLABORATEUR',$userRole)) {
                $userCollaborateur[]=$user;
            }
        }
        $collab=count($userCollaborateur);
        $commercial=count($userCommercial);
        $countAvailable=count($userAvailable);
        $userRole=$this->getUser()->getRoles();
        if (in_array('ROLE_ADMIN',$userRole)) {
            $isAdmin=true;
        } else {
            $isAdmin=false;
        }
        $userModif=$this->getDoctrine()->getRepository(User::class)->lastModif();

        $myColab=$this->getDoctrine()->getRepository(User::class)->findBy(['referent'=>$this->getUser()]);

        return $this->render('commercial/dashboard.html.twig', [
            'colab' => $collab,
            'commercial'=>$commercial,
            'numAvailable'=>$countAvailable,
            'allColab' => $userCollaborateur,
            'allCommercial'=>$userCommercial,
            'available'=>$userAvailable,
            'isAdmin'=>$isAdmin,
            'profilModifNumber'=>count($userModif),
            'profilModif'=>$userModif,
            'myColab'=>$myColab
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard Commercial');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-address-card', User::class);
        yield MenuItem::linkToCrud('Missions', 'fas fa-address-card', MissionEnCours::class);
    }
}
