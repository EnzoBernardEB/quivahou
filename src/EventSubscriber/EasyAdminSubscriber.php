<?php

namespace App\EventSubscriber;

use App\Entity\Archive;
use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\MissionEnCours;
use App\Entity\TypeMission;
use \App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\CompetenceRepository;
use App\Repository\ExperienceRepository;
use App\Repository\RelationUserRepository;
use App\Repository\TypeMissionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $security;
    private $entityManager;
    private $categorieRepository;
    private array $competenceOldCategorie = [];
    private $typeMissionRepository;
    private array $experienceOldMission = [];
    private $competenceRepository;
    private $mailer;
    private $kernel;
    private $relationUserRepository;

    public function __construct(RelationUserRepository $relationUserRepository, KernelInterface $kernel, MailerInterface $mailer, Security $security, EntityManagerInterface $entityManager, CompetenceRepository $competenceRepository, CategorieRepository $categorieRepository, TypeMissionRepository $typeMissionRepository, ExperienceRepository $experienceRepository)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->categorieRepository = $categorieRepository;
        $this->experienceRepository = $experienceRepository;
        $this->typeMissionRepository = $typeMissionRepository;
        $this->competenceRepository = $competenceRepository;
        $this->mailer = $mailer;
        $this->kernel = $kernel;
        $this->relationUserRepository = $relationUserRepository;

    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityDeletedEvent::class => ['checkBeforeSup'],
            AfterEntityDeletedEvent::class => ['addDefaultValue'],
            AfterEntityPersistedEvent::class => ['isNotAvailable'],
            AfterEntityUpdatedEvent::class => ['informUser'],
            BeforeEntityUpdatedEvent::class => ['beforeUp'],
        ];
    }

    public function checkBeforeSup(BeforeEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();
        $user = $this->security->getUser();
        $role = $user->getRoles();
        $defaultCategorie = $this->categorieRepository->findOneBy(['nom' => 'Autre']);
        $defaultMission = $this->typeMissionRepository->findOneBy(['intitule' => 'Autre']);

        if (!($entity instanceof User) && !($entity instanceof Categorie) && !($entity instanceof TypeMission) && !($entity instanceof MissionEnCours) && !($entity instanceof Competence)) {
            return;
        } elseif ($entity === $user && $user->getUserIdentifier() === $entity->getUserIdentifier()) {
            throw new \Exception('Vous ne pouvez pas vous supprimer vous même');
        } elseif ($entity === $defaultCategorie || $entity === $defaultMission) {
            throw new \Exception('Vous ne pouvez pas supprimer les valeurs par défaut.');
        } elseif ($entity instanceof Categorie && $entity !== $defaultCategorie) {
            $this->competenceOldCategorie = $this->competenceRepository->findBy(['categorie_id' => $entity->getId()]);
        } elseif ($entity instanceof TypeMission && $entity !== $defaultMission) {
            $this->experienceOldMission = $this->experienceRepository->findBy(['type' => $entity->getId()]);
        } elseif ($entity instanceof Competence) {
            $competence = $entity->getUserHasCompetences()->getValues();
            foreach ($competence as $comp) {
                $this->entityManager->remove($comp);
            }
        } elseif ($entity instanceof User && $user->getUserIdentifier() !== $entity->getUserIdentifier()) {
            $photo = $entity->getFilename()->getFilename();
            $photoPath = $this->kernel->getProjectDir() . '\public\uploads\photo\\' . $photo;
            unlink($photoPath);
            $doc = $entity->getDocuments()->getValues();
            foreach ($doc as $document) {
                $docPath = $this->kernel->getProjectDir() . '\public\uploads\document\\' . $document->getFilename();
                unlink($docPath);
                $entity->removeDocument($document);
            }
            $userRelation = $this->relationUserRepository->findBy(['user' => $entity]);
            $userReqRelation = $this->relationUserRepository->findBy(['requestUser' => $entity]);
            foreach ($userRelation as $rel) {
                $this->entityManager->remove($rel);
            }
            foreach ($userReqRelation as $rel) {
                $this->entityManager->remove($rel);
            }
            $archiveNom = $entity->getNom();
            $archivePrenom = $entity->getPrenom();
            $archiveEmail = $entity->getEmail();
            $competence = $entity->getUserHasCompetences()->getValues();
            $experience = $entity->getExperience()->getValues();
            $archiveAdresse = $entity->getAdresse()->getLabel();
            $archiveCompetence = [];

            foreach ($competence as $com) {
                $competenceUser = $this->competenceRepository->findOneBy(['id' => $com->getCompetence()->getId()])->getNom();
                if ($com->getMaitrise() === 0) {
                    $maitrise = 'débutant';
                } elseif ($com->getMaitrise() === 1) {
                    $maitrise = 'intermédiaire';
                } else {
                    $maitrise = 'avancé';
                }
                $archiveCompetence[] = '- ' . $competenceUser . ' maitrise ' . $maitrise;
                $this->entityManager->remove($com);
            }
            $ancienneExp = [];
            $qivahouExp = [];
            foreach ($experience as $exp) {
                if ($exp->getEntreprise() === null) {
                    $ancienneExp[] = '- ' . $exp->getNom() . ', ' . $exp->getDescriptif();
                } else {
                    $qivahouExp[] = '- ' . $exp->getEntreprise()->getNom() . ' : ' . $exp->getNom() . ', ' . $exp->getDescriptif();
                }
            }
            $archiveExp = (object)array("ancien" => $ancienneExp, "qivahou" => $qivahouExp);
            $archive = new Archive();
            $archive->setNom($archiveNom);
            $archive->setPrenom($archivePrenom);
            $archive->setEmail($archiveEmail);
            $archive->setAdresse($archiveAdresse);
            $archive->setCompetence($archiveCompetence);
            $archive->setExperience($archiveExp);

            $this->entityManager->persist($archive);

        }
        //si on veut empecher en plus de supprimer les admins
//        elseif (in_array("ROLE_ADMIN",$role) || $user->getUserIdentifier()===$entity->getUserIdentifier() ) {
//            throw new \Exception('Vous ne pouvez pas vous supprimer en tant qu\'admin');
//        }
    }

    public function addDefaultValue(AfterEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Categorie) {
            $defaultCategorie = $this->categorieRepository->findOneBy(['nom' => 'Autre']);
            foreach ($this->competenceOldCategorie as $competence) {
                $competence->setCategorieId($defaultCategorie);
                $this->entityManager->persist($competence);
            }
            $this->entityManager->flush();
        } elseif ($entity instanceof TypeMission) {
            $defaultMission = $this->typeMissionRepository->findOneBy(['intitule' => 'Autre']);
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
        if ($entity instanceof MissionEnCours) {
            $user = $entity->getEmploye();
            $user->setIsAvailable(false);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    public function informUser(AfterEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof User) {
            $email = (new Email())
                ->from('admin@qivahou.net')
                ->to($entity->getEmail())
                ->subject('Votre profil vient d\'être validé ou modifié')
                ->text('
                    Votre profil vient d\'être validé ou modifié, veuillez vous connecter à votre compte.
                    L\'équipe Qivahou
                ')
                ->html('<h1>Votre profil vient d\'être validé ou modifié</h1> <br> <p>
                Votre profil vient d\'être validé ou modifié, veuillez vous connecter à votre compte. <br>
                    L\'équipe Qivahou </p>');
        }
        $this->mailer->send($email);
        if ($entity->getIsAccepted() === true) {
            $entity->setRoles(['ROLE_COLLABORATEUR']);
            $this->entityManager->persist($entity);
        }

    }

    public function beforeUp(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity->getIsAccepted() === true && in_array('ROLE_ADMIN', $entity->getRoles(), true)) {
            $entity->setRoles(['ROLE_COLLABORATEUR', 'ROLE_ADMIN']);
            $this->entityManager->persist($entity);
        } elseif ($entity->getIsAccepted() === true && in_array('ROLE_COMMERCIAL', $entity->getRoles(), true)) {
            $entity->setRoles(['ROLE_COMMERCIAL']);
            $this->entityManager->persist($entity);
        }elseif($entity->getIsAccepted() === true && !in_array('ROLE_ADMIN', $entity->getRoles(), true) && !in_array('ROLE_COMMERCIAL', $entity->getRoles(), true)){
            $entity->setRoles(['ROLE_COLLABORATEUR']);
            $this->entityManager->persist($entity);
        } elseif ($entity->getIsAccepted() === false && !in_array('ROLE_ADMIN', $entity->getRoles(), true) && !in_array('ROLE_COMMERCIAL', $entity->getRoles(), true)) {
            $entity->setRoles(['ROLE_CANDIDAT']);
            $this->entityManager->persist($entity);
        }

    }
}