<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', \Symfony\Component\Form\Extension\Core\Type\SearchType::class,[
                'label'=> 'Rechercher',
                'attr'=> [
                    'class'=>'form-control',
                    'placeholder'=>"Compétence -> ex : php , Collaborateur -> ex : bernard"
                ]
            ])
            ->add('option',ChoiceType::class, [
                'label'=>'Par ',
                'choices' => [
                    'Competence' => 'competence',
                    'Avec volonté d\approfondir' => 'apprécier',
                    'Niveau'=>'niveau',
                    'Nom' => 'collab',
                ],
                'empty_data'=>'Competence'
            ])
            ->add('Rechercher',SubmitType::class,[
                'attr' => ['class' => 'p-2 border-2  bg-gray-900 text-gray-200 rounded-2xl mt-4']
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
