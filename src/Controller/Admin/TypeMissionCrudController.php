<?php

namespace App\Controller\Admin;

use App\Entity\TypeMission;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeMissionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeMission::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
