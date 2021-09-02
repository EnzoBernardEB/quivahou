<?php

namespace App\Controller\Commercial;

use App\Entity\MissionEnCours;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MissionEnCoursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MissionEnCours::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $userAvailable = AssociationField::new('employe')
            ->setLabel('Employé à envoyer')
            ->setFormTypeOption(
                'query_builder', function (UserRepository $userRepository) {
                $roleAdmin = '"ROLE_ADMIN"';
                $roleCommercial = '"ROLE_COMMERCIAL"';
                $roleCollaborateur = '"ROLE_COLLABORATEUR"';
                return $userRepository->createQueryBuilder('u')
                    ->andWhere('JSON_CONTAINS(u.roles, :roleCo) = 1 and JSON_CONTAINS(u.roles, :roleA) = 0 and JSON_CONTAINS(u.roles, :roleC) = 0')
                    ->andWhere('u.isAvailable = :value')
                    ->setParameter('roleA', $roleAdmin)
                    ->setParameter('roleC', $roleCommercial)
                    ->setParameter('roleCo', $roleCollaborateur)
                    ->setParameter('value', 1)
                    ->orderBy('u.modifDate', 'ASC');
            });


        return [
            TextField::new('titre'),
            TextField::new('description'),
            $userAvailable,
            AssociationField::new('entreprise'),
        ];
    }

}
