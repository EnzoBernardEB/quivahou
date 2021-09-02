<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\RelationUser;
use App\Entity\User;
use App\Form\ExperienceType;
use App\Repository\RelationUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewProfilController extends AbstractController
{
    #[Route('/view/profil/{id}', name: 'view_profil')]
    public function index(User $user, RelationUserRepository $relationUserRepository): Response
    {
        $isVisible=false;
        $isMyCollegue=$relationUserRepository->canSee($user->getId());
        foreach ($isMyCollegue as $collegue) {
            if($collegue->getIsAccepted()) {
                $isVisible=true;
            }
        }
        $userRoles=$this->getUser()->getRoles();
        if (in_array('ROLE_COMMERCIAL',$userRoles) or in_array('ROLE_ADMIN',$userRoles)){
            $isVisible=true;
        }
        $userAnniversaryDate=$user->getAnniversaryDate();
        $actualDate = new \DateTime('now');
        $anciennete = date_diff($actualDate,$userAnniversaryDate);

        $isPhoto=file_exists('uploads/photo/'.$user->getFilename());

        return $this->render('view_profil/index.html.twig', [
            'user' => $user,
            'visible'=>$isVisible,
            'dateAnniversaire'=>$userAnniversaryDate,
            'anciennete'=>$anciennete,
            'isPhoto'=>$isPhoto
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
    #[ParamConverter('id', class: User::class)]
    public function requestRelation(User $user,UserRepository $userRepository ,EntityManagerInterface $entityManager)
    {
        $userLogged=$this->getUser();
        $userID=$userRepository->findOneBy(['id'=>$userLogged->getId()]);
        $relationUser = new RelationUser();
        $relationUser->setUser($userID);
        $relationUser->setRequestUser($user);
        $relationUser->setPending(true);
        $relationUser->setIsAccepted(false);
        $relationUser->setIsDeny(false);
        $entityManager->persist($relationUser);

        $entityManager->flush();


        return $this->redirect('/view/profil/'.$user->getId());
    }



}
