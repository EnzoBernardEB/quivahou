<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\RelationUser;
use App\Entity\User;
use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewProfilController extends AbstractController
{
    #[Route('/view/profil/{id}', name: 'view_profil')]
    public function index(User $user): Response
    {

        return $this->render('view_profil/index.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('profil/{id}/add/experience', name: 'add_experience_user')]
    public function addExperienceToUser(Request$request,EntityManagerInterface $entityManager, User $user): Response
    {
        $experience = new Experience();
        $formAddExperience = $this->createForm(ExperienceType::class,$experience);
        $formAddExperience->handleRequest($request);

        if($formAddExperience->isSubmitted() && $formAddExperience->isValid()) {
            $experience=$formAddExperience->getData();
            $competenceUsed=$experience->getCompetenceUtilise();
            $countCompetenceInExp=count($competenceUsed);
            if($countCompetenceInExp>0) {
                $experience->setUser($user);
                $entityManager->persist($experience);
                $entityManager->flush();
                $this->addFlash('successExp','Experience bien enregistrée.');

            } else {
                $this->addFlash('atLeastOneExp','Veuillez séléctionner au moins une compétence.');
            }

        }


        return $this->render('add_experience/index.html.twig', [
            'form' => $formAddExperience->createView(),
            'userProfil'=>$user,
        ]);
    }

    #[Route('/send/relRequest/{id}', name: 'sendRelRequest')]
    public function requestRelation(User $user, RelationUser $relationUser, EntityManagerInterface $entityManager, Request $request)
    {
        dd($user);
        $userLogged=$this->getUser();
        $relationUser->setUser($userLogged);
        $relationUser->setRequestUser($user);
        $relationUser->setPending(true);
        $relationUser->setIsAccepted(false);
        $entityManager->persist($relationUser);
        $entityManager->flush();

        $locale = $request->get('_locale');
        $this->get('session')->set('_locale', $locale);
        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);

    }



}
