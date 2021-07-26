<?php

namespace App\Form;

use App\Entity\Competence;
use App\Entity\Experience;
use App\Entity\TypeMission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('descriptif')
            ->add('competence_utilise',EntityType::class, [
                'class'=>Competence::class,
                'multiple'=>true,
                'expanded'=>true,
                'label'=>'Selectionner les compétences utilisés.',
                'label_attr'=>[
                    'class'=>'text-2xl mb-2 mt-8'
                ],
                'choice_label'=> function (Competence $competence) {
                    return $competence->getNom();
                }
            ])
            ->add('type',EntityType::class, [
                'class'=>TypeMission::class,
                'label'=>'Selectionner le type de mission.',
                'label_attr'=>[
                    'class'=>'text-2xl mb-2 mt-8'
                ],
                'choice_label'=> function (TypeMission $type) {
                    return $type->getIntitule();
                },

            ])
            ->add('Ajouter',SubmitType::class,[
                'attr' => ['class' => 'p-2 border-2  bg-gray-900 text-gray-200 rounded-2xl mt-4']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
