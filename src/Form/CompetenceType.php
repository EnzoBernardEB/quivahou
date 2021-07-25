<?php

namespace App\Form;

use App\Entity\Competence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',EntityType::class, [
                'class'=>Competence::class,
                'label'=>'Selectionner vos compétences.',
                'label_attr'=>[
                    'class'=>'text-2xl mb-2'
                ],
                'choice_label'=> function ($competence) {
                return $competence->getNom();
                }
            ])
            ->add('Ajouter',SubmitType::class,[
                'attr' => ['class' => 'p-2 border-2  bg-gray-900 text-gray-200 rounded-2xl mt-4']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
        ]);
    }
}
