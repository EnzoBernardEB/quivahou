<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commercial',EntityType::class,[
                'class'=>User::class,
                'label'=>'Selectionner le commercial référent :',
                'label_attr'=>[
                    'class'=>'text-2xl mb-2'
                ],
                'query_builder' => function (UserRepository $userRepository) {
                    $role = '"ROLE_COMMERCIAL"';
                    return $userRepository->createQueryBuilder('u')
                        ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
                        ->setParameter('role',$role);
                }
            ])
            ->add('collaborateur',EntityType::class,[
                'class'=>User::class,
                'label'=>'Selectionner le collaborateur :',
                'label_attr'=>[
                    'class'=>'text-2xl mb-2'
                ],
                'query_builder' => function (UserRepository $userRepository) {
                    $role = '"ROLE_COLLABORATEUR"';

                    return $userRepository->createQueryBuilder('u')
                        ->andWhere('JSON_CONTAINS(u.roles, :role) = 1')
                        ->setParameter('role',$role);
                }
            ])
            ->add('Assigner',SubmitType::class,[
                'attr'=>['class'=>'p-2 border-2 bg-gray-900 text-gray-200 rounded-2xl mt-4']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
