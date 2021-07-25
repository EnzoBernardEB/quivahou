<?php

namespace App\Controller;

use App\Entity\Experience;
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
        $formAddExperience = $this->createForm(Experience::class);
        $formAddExperience->handleRequest($request);

        if ($formAddExperience->isSubmitted() && $formAddExperience->isValid()) {

            $userExperience = $formAddExperience->getData();


            $user=$this->getUser();

            $user->addCompetenceId($competence);


            $entityManager->persist($user);
            $entityManager->flush();
            $this->redirectToRoute('add_competence');
        }
        return $this->render('add_experience/index.html.twig', [
            'controller_name' => 'AddExperienceController',
        ]);
    }
}
