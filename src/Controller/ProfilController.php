<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    #[IsGranted('ROLE_IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        $user=$this->getUser();
        $userTel=$user->getTelephone();
        $userTelLength=count(str_split($userTel));
        if($userTelLength%2===0){
            $userTel=wordwrap($userTel,2,' ',true);
        }else{
            $userTel=wordwrap($userTel,3,' ',true);
        }



        return $this->render('profil/index.html.twig', [
            'telephone' => $userTel,
        ]);
    }
}
