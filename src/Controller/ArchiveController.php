<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArchiveController extends AbstractController
{
    #[Route('/archive', name: 'archive')]
    public function index(): Response
    {
        $archives = $this->getDoctrine()->getRepository('App:Archive')->findAll();
        return $this->render('archive/index.html.twig', [
            'archives' => $archives,
        ]);
    }
}
