<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\UserHasCompetence;
use App\Form\CompetenceType;
use App\Form\CompetenceUserType;
use App\Repository\CompetenceRepository;
use App\Repository\UserHasCompetenceRepository;
use App\Repository\UserRepository;
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
                $user->setModifDate(new \DateTime());
                $entityManager->persist($userCompetence);
                $entityManager->flush();
                $this->addFlash('successAdd','Compétence enregistrée.');

            }


            $this->redirectToRoute('add_competence');
            }


        return $this->render('add_competence/index.html.twig', [
            'form' => $formAddCompetence->createView(),
        ]);
    }
    #[Route('/profil/edit/competence/{id}', name: 'edit_competence', methods: ['GET', 'POST'])]
    public function edit(Request $request,EntityManagerInterface $entityManager ,UserHasCompetence $userHasCompetence): Response
    {
        $formEditCompetence = $this->createForm(CompetenceUserType::class, $userHasCompetence);
        $formEditCompetence->handleRequest($request);

        if ($formEditCompetence->isSubmitted() && $formEditCompetence->isValid()) {
            $entityManager->flush();
            if($this->getUser()->getIsCompleted() ===true) {
                return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('add_competence', [], Response::HTTP_SEE_OTHER);
        }
        $user=$this->getUser();
        $user->setModifDate(new \DateTime());
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->renderForm('add_competence/edit.html.twig', [
            'competence' => $userHasCompetence,
            'form' => $formEditCompetence,
        ]);
    }

    #[Route('/profil/remove/competence/{id}', name: 'remove_competence')]
    public function delete(EntityManagerInterface $entityManager, UserHasCompetence $userHasCompetence, UserRepository $userRepository, UserHasCompetenceRepository $userHasCompetenceRepository)
    {

        $ownerCompetence= $userRepository->findOneBy(['id'=>$userHasCompetence->getUser()->getId()]);
        $userCompetence = $userHasCompetenceRepository->findBy(['user'=>$ownerCompetence->getId()]);
        if(count($userCompetence) > 1) {
            $entityManager->remove($userHasCompetence);
            $entityManager->flush();
        } else {
            $this->addFlash('oneCompetence','Vous devez possedez au moins une compétence.');
        }


        return $this->redirectToRoute('app_profil');
    }
}
