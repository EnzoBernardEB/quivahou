<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddCompetenceController extends AbstractController
{
    #[Route('profil/add/competence', name: 'add_competence')]
    public function index(Request$request,EntityManagerInterface $entityManager, CompetenceRepository $competenceRepository): Response
    {

        $formAddCompetence = $this->createForm(CompetenceType::class);
        $formAddCompetence->handleRequest($request);

        if ($formAddCompetence->isSubmitted() && $formAddCompetence->isValid()) {

            $userCompetence = $formAddCompetence->getData();
            $competenceName= $userCompetence->getNom();
            $competence=$competenceRepository->findOneBy(['nom'=>$competenceName]);

            $user=$this->getUser();

            $user->addCompetenceId($competence);


            $entityManager->persist($user);
            $entityManager->flush();
            $this->redirectToRoute('add_competence');
            }



        return $this->render('add_competence/index.html.twig', [
            'formCompetence' => $formAddCompetence->createView(),
        ]);
    }
}