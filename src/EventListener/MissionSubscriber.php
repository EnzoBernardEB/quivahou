<?php

namespace App\EventListener;

use App\Entity\Experience;
use App\Entity\MissionEnCours;
use App\Entity\Product;
use App\Repository\TypeMissionRepository;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MissionSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $typeMissionRepository;
    private $entityManager;

    public function __construct(MailerInterface $mailer, TypeMissionRepository $typeMissionRepository,EntityManagerInterface $entityManager)
    {
        $this->mailer=$mailer;
        $this->typeMissionRepository=$typeMissionRepository;
        $this->entityManager=$entityManager;
    }
    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove
        ];
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('remove', $args);
    }


    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ($entity instanceof MissionEnCours) {
            $typeMission=$this->typeMissionRepository->findOneBy(['intitule'=>'Mission Quivahou']);
            $user=$entity->getEmploye();
            $entreprise=$entity->getEntreprise();
            $experience = new Experience();
            $experience->setNom($entity->getTitre());
            $experience->setDescriptif($entity->getDescription());
            $experience->setEntreprise($entreprise);
            $experience->setUser($user);
            $experience->setType($typeMission);

            $this->entityManager->persist($experience);
            $this->entityManager->flush();

            $email = (new Email())
                ->from('admin@qivahou.net')
                ->to($user->getEmail())
                ->subject('Fin de mission')
                ->text('
                    Votre mission au sein de '.$entreprise.' a touché à sa fin.
                    Cette mission à été rajouter dans vos experiences, veuillez la modifier et rajouter toutes les compétences utilisées lors de cette mission.
                    Cordialement, 
                    L\équipe Qivahou 
                ')
                ->html('<h1>Fin de mission chez '.$entreprise.'</h1> <br> <p>Cette mission à été rajouter dans vos experiences, veuillez la modifier et rajouter toutes les compétences utilisé lors de cette mission.
                    Cordialement,<br> 
                    L\'équipe Qivahou</p>');
            $this->mailer->send($email);
        }

    }
}
