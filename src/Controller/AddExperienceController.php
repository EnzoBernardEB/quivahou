<?php

namespace App\Controller;

use App\Entity\Experience;
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
        $experience = new Experience();
        $formAddExperience = $this->createForm(ExperienceType::class,$experience);
        $formAddExperience->handleRequest($request);

        if($formAddExperience->isSubmitted() && $formAddExperience->isValid()) {
            $experience=$formAddExperience->getData();
            $user=$this->getUser();
            $experience->setUser($user);
            $entityManager->persist($experience);
            $entityManager->flush();
        }


        return $this->render('add_experience/index.html.twig', [
            'formExperience' => $formAddExperience->createView(),
        ]);
    }
}
