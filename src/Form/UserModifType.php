<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label'=>'Nom : '
            ])
            ->add('prenom',TextType::class,[
                'label'=>'Prénom : '
            ])
            ->add('dateDeNaissance',BirthdayType::class,[
                'label'=>'Date de naissance : ',
                'format'=>'ddMMyyyy',
            ])
            ->add('email',EmailType::class,[
                'label'=>'Email : '
            ])
            ->add('telephone',TelType::class,[
                'label'=>'Téléphone : '
            ])
            ->add('mofifier',SubmitType::class,[
                'label'=>'Mofifier',
                'attr'=>['class'=>
                    'border border-gray-400 p-2 rounded-2xl
                    hover:border-gray-900 
                    bg-gray-900 hover:bg-gray-500
                    text-gray-300 hover:text-gray-900
                     ']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
