<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionFiniController extends AbstractController
{
    #[Route('/inscription/fini', name: 'inscription_fini')]
    public function index(): Response
    {
        return $this->render('inscription_fini/index.html.twig', [
            'controller_name' => 'InscriptionFiniController',
        ]);
    }
}
