<?php

namespace App\Controller;

use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddExperienceController extends AbstractController
{
    #[Route('profil/add/experience', name: 'add_experience')]
    public function index(Request$request,EntityManagerInterface $entityManager): Response
    {
        $formAddExperience = $this->createForm(ExperienceType::class);
        $formAddExperience->handleRequest($request);


        return $this->render('add_experience/index.html.twig', [
            'formExperience' => $formAddExperience->createView(),
        ]);
    }
}
