<?php

namespace App\EventSubscriber;

use \App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security=$security;
    }
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityDeletedEvent::class=>['checkIfAdmin']
        ];
    }
    public function checkIfAdmin (BeforeEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();
        $user = $this->security->getUser();
        $role=$user->getRoles();

        if(!($entity instanceof User)) {
            return;
        } elseif ($user->getUserIdentifier()===$entity->getUserIdentifier() ) {
            throw new \Exception('Vous ne pouvez pas vous supprimer vous même');
        }
        //si on veut empecher en plus de supprimer les admins
//        elseif (in_array("ROLE_ADMIN",$role) || $user->getUserIdentifier()===$entity->getUserIdentifier() ) {
//            throw new \Exception('Vous ne pouvez pas vous supprimer en tant qu\'admin');
//        }


    }
}