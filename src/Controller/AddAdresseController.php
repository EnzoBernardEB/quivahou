<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdresseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddAdresseController extends AbstractController
{
    #[Route('/add/adresse', name: 'add_adresse')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse= new  Adress();
        $formAddAdress=$this->createForm(AdresseType::class,$adresse);
        $formAddAdress->handleRequest($request);



        return $this->render('add_adresse/index.html.twig', [
            'controller_name' => 'AddAdresseController',
        ]);
    }
}
