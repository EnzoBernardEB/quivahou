<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

class RegistrationFormType extends AbstractType
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=>'Acceptez notre politique de confidentialité',
                'attr'=>['class'=>'ml-4'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez acceptez notre politique de confidentialité.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type'=>PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message'=>"Erreur dans vos mots de passe",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} characteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'first_options'  => ['label' => 'Mot de passe','attr'=>[
                    'class'=>'flex flex-col mb-2'
                ]],
                'second_options' => ['label' => 'Confirmation du mot de passe','attr'=>[
                    'class'=>'flex flex-col mb-2'
                ]],
            ])
            ->add('postuler',SubmitType::class,[
                'label'=>'Postuler',
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
