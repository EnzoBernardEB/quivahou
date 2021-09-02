<?php

namespace App\EventSubscriber;

use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Experience;
use App\Entity\MissionEnCours;
use App\Entity\TypeMission;
use \App\Entity\User;
use App\Entity\UserHasCompetence;
use App\Repository\CategorieRepository;
use App\Repository\CompetenceRepository;
use App\Repository\ExperienceRepository;
use App\Repository\TypeMissionRepository;
use App\Repository\UserHasCompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $security;
    private $entityManager;
    private $categorieRepository;
    private array $competenceOldCategorie=[];
    private $typeMissionRepository;
    private array $experienceOldMission=[];
    private $competenceRepository;
    private $experienceRepository;
    private $mailer;
    public function __construct(MailerInterface $mailer,Security $security,EntityManagerInterface $entityManager ,CompetenceRepository $competenceRepository ,CategorieRepository $categorieRepository ,TypeMissionRepository $typeMissionRepository ,ExperienceRepository $experienceRepository, UserHasCompetenceRepository $userHasCompetenceRepository)
    {
        $this->security=$security;
        $this->entityManager=$entityManager;
        $this->categorieRepository=$categorieRepository;
        $this->experienceRepository=$experienceRepository;
        $this->typeMissionRepository=$typeMissionRepository;
        $this->competenceRepository=$competenceRepository;
        $this->mailer=$mailer;
    }
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityDeletedEvent::class=>['checkBeforeSup'],
            AfterEntityDeletedEvent::class=>['addDefaultValue'],
            AfterEntityPersistedEvent::class=>['isNotAvailable']
        ];
    }
    public function checkBeforeSup (BeforeEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();
        $user = $this->security->getUser();
        $role=$user->getRoles();
        $defaultCategorie=$this->categorieRepository->findOneBy(['nom'=>'Autre']);
        $defaultMission=$this->typeMissionRepository->findOneBy(['intitule'=>'Autre']);

        if(!($entity instanceof User) && !($entity instanceof Categorie) && !($entity instanceof TypeMission) && !($entity instanceof MissionEnCours)) {
            return;
        } elseif ($entity===$user && $user->getUserIdentifier()===$entity->getUserIdentifier() ) {
            throw new \Exception('Vous ne pouvez pas vous supprimer vous même');
        } elseif ($entity===$defaultCategorie || $entity===$defaultMission ) {
            throw new \Exception('Vous ne pouvez pas supprimer les valeurs par défaut.');
        } elseif ($entity instanceof Categorie && $entity !== $defaultCategorie ) {
            $this->competenceOldCategorie=$this->competenceRepository->findBy(['categorie_id'=>$entity->getId()]);
        } elseif ($entity instanceof TypeMission && $entity!==$defaultMission) {
            $this->experienceOldMission=$this->experienceRepository->findBy(['type'=>$entity->getId()]);
        }elseif ($entity instanceof MissionEnCours) {
            $typeMission=$this->typeMissionRepository->findOneBy(['intitule'=>'Mission Quivahou']);
            $user=$entity->getEmploye();
            $entreprise=$entity->getEntreprise();
            $experience = new Experience();
            $experience->setNom($entity->getTitre());
            $experience->setDescriptif($entity->getDescription());
            $experience->setEntreprise($entreprise);
            $experience->setUser($user);
            $experience->setType($typeMission);
            $user->setIsAvailable(true);
            $this->entityManager->persist($experience);
            $this->entityManager->persist($user);
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
        //si on veut empecher en plus de supprimer les admins
//        elseif (in_array("ROLE_ADMIN",$role) || $user->getUserIdentifier()===$entity->getUserIdentifier() ) {
//            throw new \Exception('Vous ne pouvez pas vous supprimer en tant qu\'admin');
//        }
    }
    public function addDefaultValue(AfterEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if($entity instanceof Categorie) {
            $defaultCategorie=$this->categorieRepository->findOneBy(['nom'=>'Autre']);
            foreach($this->competenceOldCategorie as $competence) {
                $competence->setCategorieId($defaultCategorie);
                $this->entityManager->persist($competence);
            }
            $this->entityManager->flush();
        } elseif ($entity instanceof TypeMission) {
            $defaultMission=$this->typeMissionRepository->findOneBy(['intitule'=>'Autre']);
            foreach ($this->experienceOldMission as $experience) {
                $experience->setType($defaultMission);
                $this->entityManager->persist($experience);
            }
            $this->entityManager->flush();
        }
    }
    public function isNotAvailable(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if($entity instanceof MissionEnCours) {
            $user=$entity->getEmploye();
            $user->setIsAvailable(false);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}