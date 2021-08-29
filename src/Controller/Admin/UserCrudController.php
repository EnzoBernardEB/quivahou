<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('prenom'),
            ArrayField::new('roles'),
            TextField::new('email'),
            DateField::new('date_de_naissance')->setFormat('dd-MM-Y'),
            DateField::new('anniversaryDate')->setFormat('d-MM-Y'),            TelephoneField::new('telephone'),
            BooleanField::new('isVerified'),
            BooleanField::new('isCompleted'),
            BooleanField::new('isAccepted'),
        ];
    }
}
