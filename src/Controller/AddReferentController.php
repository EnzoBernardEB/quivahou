<?php

namespace App\Controller;

use App\Form\ReferentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class AddReferentController extends AbstractController
{
    #[Route('/add/referent', name: 'add_referent'), IsGranted('ROLE_ADMIN')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $formReferent= $this->createForm(ReferentType::class);
        $formReferent->handleRequest($request);

        if($formReferent->isSubmitted()) {
            $commercial = $formReferent->get('commercial')->getData();
            $collab= $formReferent->get('collaborateur')->getData();
            $actualReferent=$collab->getReferent();
            if($actualReferent===NULL){
                $email = (new Email())
                    ->from($this->getUser()->getEmail())
                    ->to($collab->getEmail())
                    ->cc($commercial->getEmail())
                    ->subject('Assignation référent')
                    ->text('
                    Un  référent vous à été assigné.
                    l\'équipe Qivahou
                ')
                    ->html('<h1>Assignation référent</h1> <br> <p>Un référent vous à été assigné : 
                    '.$commercial->getNom().' '.$commercial->getPrenom().'. 
                    L\'équipe Qivahou </p>');
                $mailer->send($email);
                $collab->setReferent($commercial);
                $entityManager->persist($collab);
                $this->addFlash('ref', 'Référent bien assigné');
            } elseif ($actualReferent!== NULL && $actualReferent===$commercial ) {
                $this->addFlash('alredyRef', 'Ce commercial est déja le référent de ce collaborateur');
            } elseif ($actualReferent !== NULL && $actualReferent !==$commercial) {
                $collab->setReferent($commercial);
                $entityManager->persist($collab);
                $this->addFlash('newRef','Le changement à été effectuer. Un mail à été envoyé aux deux parties.');
                $email = (new Email())
                    ->from($this->getUser()->getEmail())
                    ->to($collab->getEmail())
                    ->cc($commercial->getEmail())
                    ->subject('Assignation de mission')
                    ->text('
                    Un nouveau référent vous à été assigné.
                    l\'équipe Qivahou
                ')
                    ->html('<h1>Nouveau référent</h1> <br> <p>Un nouveau référent vous à été assigné : 
                    '.$commercial->getNom().' '.$commercial->getPrenom().'. 
                    L\'équipe Qivahou </p>');
                $mailer->send($email);
            }
            $entityManager->flush();
        }

        return $this->render('add_referent/index.html.twig', [
            'formRef' => $formReferent->createView(),
        ]);
    }
}
