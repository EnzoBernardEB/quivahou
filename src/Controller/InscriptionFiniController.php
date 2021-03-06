<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionFiniController extends AbstractController
{
    #[Route('profil/inscription/fini', name: 'app_inscription_fini')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $user->setIsCompleted(true);
        $roles=$user->getRoles();
        $roles[]='ROLE_CANDIDAT';
        $user->setRoles($roles);
        $user->setModifDate(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('profilComplete','Votre profil est bien complété, veuillez vous reconnecter');

        return $this->redirectToRoute('app_login');
    }
}
