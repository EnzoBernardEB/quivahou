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
        $user->setIsVerified(false);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_profil');
    }
}
