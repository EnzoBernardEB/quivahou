<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Form\SearchType;
use App\Repository\CompetenceRepository;
use App\Repository\TypeMissionRepository;
use App\Repository\UserHasCompetenceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(Request $request, CompetenceRepository $competenceRepository, TypeMissionRepository $typeMissionRepository, UserRepository $userRepository,UserHasCompetenceRepository $userHasCompetenceRepository): Response
    {
        $searchForm=$this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        $resultToDisplay=[];
        $competenceHigh=[];
        $competenceMedium=[];
        $competenceLow=[];
        $option='';

        if ($searchForm->isSubmitted() && $searchForm->isValid()){

            $query=$searchForm->get('query')->getData();
            $option=$searchForm->get('option')->getData();
            switch ($option){
                case 'competence':
                    $competence=$competenceRepository->findOneBy(['nom'=>$query]);
                    $userhas=$userHasCompetenceRepository->findBy(['competence'=>$competence]);
                    foreach ($userhas as $user){
                        $resultToDisplay[]=$user->getUser();
                    }
                    break;
                case 'collab':
                    $result=$userRepository->findCollab($query);
                    if(!empty($result)) {
                        $resultToDisplay[]=$result;
                    }

                    break;
                case 'apprécier':
                    $competence=$competenceRepository->findOneBy(['nom'=>$query]);
                    $userLike=$userHasCompetenceRepository->findCompetenceIsLiked($competence);
                    foreach ($userLike as $user){
                        $resultToDisplay[]=$user->getUser();
                    }
                    break;
                case 'niveau':
                    $competence=$competenceRepository->findOneBy(['nom'=>$query]);
                    $userhas=$userHasCompetenceRepository->findBy(['competence'=>$competence]);
                    foreach ($userhas as $user){
                        if ($user->getMaitrise()===2) {
                            $competenceHigh[]=$user->getUser();
                        } elseif ($user->getMaitrise()===1) {
                            $competenceMedium[]=$user->getUser();
                        }else {
                            $competenceLow[]=$user->getUser();
                        }
                    }

            }

        }
        $resultCount=count($resultToDisplay);
        $niveauCount=count($competenceHigh)+count($competenceMedium)+count($competenceLow);

        if ($option !== 'niveau') {
            return $this->render('search/index.html.twig', [
                'form' => $searchForm->createView(),
                'result'=>$resultToDisplay,
                'resultCount'=>$resultCount
            ]);
        } else {
            return $this->render('search/index.html.twig', [
                'form' => $searchForm->createView(),
                'high'=>$competenceHigh,
                'medium'=>$competenceMedium,
                'low'=>$competenceLow,
                'niveauCount'=>$niveauCount,
            ]);
        }

    }
}
