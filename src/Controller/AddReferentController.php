<?php

namespace App\Controller;

use App\Form\ReferentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddReferentController extends AbstractController
{
    #[Route('/add/referent', name: 'add_referent'), IsGranted('ROLE_ADMIN')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formReferent= $this->createForm(ReferentType::class);
        $formReferent->handleRequest($request);

        if($formReferent->isSubmitted()) {
            $commercial = $formReferent->get('commercial')->getData();
            $collab= $formReferent->get('collaborateur')->getData();

            $collab->addUser($commercial);
            $entityManager->persist($collab);
            $entityManager->flush();
        }

        return $this->render('add_referent/index.html.twig', [
            'formRef' => $formReferent->createView(),
        ]);
    }
}
