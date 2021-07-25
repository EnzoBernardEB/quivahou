<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddExperienceController extends AbstractController
{
    #[Route('/add/experience', name: 'add_experience')]
    public function index(): Response
    {
        return $this->render('add_experience/index.html.twig', [
            'controller_name' => 'AddExperienceController',
        ]);
    }
}
