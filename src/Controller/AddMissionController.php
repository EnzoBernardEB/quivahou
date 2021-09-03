<?php

namespace App\Controller;

use App\Entity\MissionEnCours;
use App\Entity\User;
use App\Form\MissionType;
use App\Repository\MissionEnCoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class AddMissionController extends AbstractController
{
    #[Route('/add/mission/{id}', name: 'add_mission'),IsGranted('ROLE_COMMERCIAL')]
    public function index(Request $request,EntityManagerInterface $entityManager ,User $user,MailerInterface $mailer): Response
    {
        $newMission= new MissionEnCours();
        $formMission=$this->createForm(MissionType::class,$newMission);
        $formMission->handleRequest($request);

        if ($formMission->isSubmitted() && $formMission->isValid() ) {
            if ($user->getIsAvailable()===true) {
                $newMission=$formMission->getData();
                $entreprise=$formMission->get('entreprise')->getData();
                $titre=$formMission->get('titre')->getData();
                $description=$formMission->get('description')->getData();
                $newMission->setEmploye($user);
                $user->setIsAvailable(false);
                $entityManager->persist($newMission);
                $entityManager->persist($user);
                $entityManager->flush();

                $email = (new Email())
                    ->from($this->getUser()->getEmail())
                    ->to($user->getEmail())
                    ->subject('Assignation de mission')
                    ->text('
                    Une mission vous à été assigné chez '.$entreprise.'.
                    Récapitulatif de mission :
                    Titre : '.$titre.'
                    Description : '.$description.'
                    Veuillez contacter votre référent pour plus de details.
                    Cordialement, 
                    L\'équipe Qivahou 
                ')
                    ->html('<h1>Une mission vous à été assigné chez '.$entreprise.'</h1> <br> <p>Récapitulatif de mission : <br>
                    Titre : '.$titre.' <br>   
                    Description : '.$description.'<br> 
                    Veuillez contacter votre référent pour plus de details.<br> 
                    Cordialement, <br> 
                    L\'équipe Qivahou </p>');
                $mailer->send($email);
                $this->addFlash('successMission','Mission bien assigné, un email à été au collaborateur.');
                return $this->redirect('/view/profil/'.$user->getId());
            } else {
                $this->addFlash('errorMission', 'Cette employé n\'est pas disponible');
                return $this->redirect('/view/profil/'.$user->getId());
            }
        }
        return $this->render('add_mission/index.html.twig', [
            'formMission' => $formMission->createView(),
        ]);
    }

    #[Route('/remove/mission/{id}', name: 'fin_mission'),IsGranted('ROLE_COMMERCIAL')]
    public function finDeMission(User $user,EntityManagerInterface $entityManager,MissionEnCoursRepository $missionEnCoursRepository)
    {
        $userMission=$missionEnCoursRepository->findOneBy(['employe'=>$user->getId()]);
        $user->setIsAvailable(true);
        $entityManager->remove($userMission);
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('finMission','Fin de mission, un email à été envoyé au collaborateur.');
        return $this->redirect('/view/profil/'.$user->getId());
    }

}
