<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExtractionCVController extends AbstractController
{
    #[Route('/extraction/{id}', name: 'extraction_cv')]
    public function index(EntityManagerInterface $entityManager,User $user): Response
    {
        $competence=$user->getUserHasCompetences()->getValues();
        $experience=$user->getExperience()->getValues();
        $adresse=$user->getAdresse()->getLabel();

        $archiveCompetence=[];

        foreach ($competence as $com) {
            $competenceUser=$this->getDoctrine()->getRepository('App:Competence')->findOneBy(['id'=>$com->getCompetence()->getId()])->getNom();
            if($com->getMaitrise() === 0 ) {
                $maitrise='débutant';
            } elseif($com->getMaitrise() === 1) {
                $maitrise='intermédiaire';
            } else {
                $maitrise='avancé';
            }
            $archiveCompetence[]='- '.$competenceUser.' maitrise '.$maitrise;
        }
        $ancienneExp=[];
        $qivahouExp=[];
        foreach ($experience as $exp) {
            if($exp->getEntreprise()===null) {
                $ancienneExp[]='- '.$exp->getNom().', '.$exp->getDescriptif();
            } else {
                $qivahouExp[]='- '.$exp->getEntreprise()->getNom().' : '.$exp->getNom().', '.$exp->getDescriptif();
            }
        }





        return $this->render('extraction_cv/index.html.twig', [
            'user' => $user,
            'competence'=>$archiveCompetence,
            'experienceAncienne'=>$ancienneExp,
            'experienceQivahou'=>$qivahouExp,
            'adresse'=>$adresse
        ]);
    }

    #[Route('/extraction/anonyme/{id}', name: 'extraction_cv_anonyme')]
    public function cvAnonyme(EntityManagerInterface $entityManager,User $user): Response
    {
        $competence=$user->getUserHasCompetences()->getValues();
        $experience=$user->getExperience()->getValues();
        $archiveCompetence=[];

        foreach ($competence as $com) {
            $competenceUser=$this->getDoctrine()->getRepository('App:Competence')->findOneBy(['id'=>$com->getCompetence()->getId()])->getNom();
            if($com->getMaitrise() === 0 ) {
                $maitrise='débutant';
            } elseif($com->getMaitrise() === 1) {
                $maitrise='intermédiaire';
            } else {
                $maitrise='avancé';
            }
            $archiveCompetence[]='- '.$competenceUser.' maitrise '.$maitrise;
        }
        $ancienneExp=[];
        $qivahouExp=[];
        foreach ($experience as $exp) {
            if($exp->getEntreprise()===null) {
                $ancienneExp[]='- '.$exp->getNom().', '.$exp->getDescriptif();
            } else {
                $qivahouExp[]='- '.$exp->getEntreprise()->getNom().' : '.$exp->getNom().', '.$exp->getDescriptif();
            }
        }

        return $this->render('extraction_cv/anonyme.html.twig', [
            'user' => $user,
            'competence'=>$archiveCompetence,
            'experienceAncienne'=>$ancienneExp,
            'experienceQivahou'=>$qivahouExp,
        ]);
    }
}
