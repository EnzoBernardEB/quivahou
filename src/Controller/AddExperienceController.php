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
            'form' => $formAddExperience->createView(),
        ]);
    }

    #[Route('/profil/edit/experience/{id}', name: 'edit_experience', methods: ['GET', 'POST'])]
    public function edit(Request $request,EntityManagerInterface $entityManager ,Experience $experience): Response
    {
        $formEditExperience = $this->createForm(ExperienceType::class, $experience);
        $formEditExperience->handleRequest($request);

        if ($formEditExperience->isSubmitted() && $formEditExperience->isValid()) {
            $entityManager->flush();
            if($this->getUser()->getIsCompleted() ===true) {
                return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('add_experience', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('add_experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $formEditExperience,
        ]);
    }
}
