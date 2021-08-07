<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\UserHasCompetence;
use App\Form\CompetenceType;
use App\Form\CompetenceUserType;
use App\Repository\CompetenceRepository;
use App\Repository\UserHasCompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddCompetenceController extends AbstractController
{
    #[Route('profil/add/competence', name: 'add_competence')]
    public function index(Request$request,EntityManagerInterface $entityManager,CompetenceRepository $competenceRepository, UserHasCompetenceRepository $userHasCompetenceRepository): Response
    {

        $userCompetence=new UserHasCompetence();
        $formAddCompetence = $this->createForm(CompetenceUserType::class,$userCompetence);
        $formAddCompetence->handleRequest($request);

        if ($formAddCompetence->isSubmitted() && $formAddCompetence->isValid()) {

            $userCompetence = $formAddCompetence->getData();
            $competenceName= $userCompetence->getCompetence()->getNom();
            $competence=$competenceRepository->findOneBy(['nom'=>$competenceName]);

            $user=$this->getUser();

            $userCompetence->setUser($user);

            $userCompetenceArray=$userHasCompetenceRepository->findBy(['user'=>$user]);
            $competenceId=[];

            foreach($userCompetenceArray as $competenceInUser) {
                $competenceId[]=$competenceInUser->getCompetence()->getId();
            }
            $checkIdCompetence = $competence->getId();

            if (in_array($checkIdCompetence,$competenceId)) {
                $this->addFlash('alreadyCompetence','Cette compétence est déja enregistrée.');
            } else {
                $userCompetence->setCompetence($competence);
                $entityManager->persist($userCompetence);
                $entityManager->flush();
                $this->addFlash('successAdd','Compétence enregistrée.');

            }


            $this->redirectToRoute('add_competence');
            }



        return $this->render('add_competence/index.html.twig', [
            'formCompetence' => $formAddCompetence->createView(),
        ]);
    }
}
