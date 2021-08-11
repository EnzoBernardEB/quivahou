<?php

namespace App\Form;

use App\Entity\Competence;
use App\Entity\UserHasCompetence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetenceUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maitrise',ChoiceType::class, [
                'label'=> 'Votre niveau dans cette compétence : ',
                'choices' => [
                    'débutant' => 0,
                    'intermediaire' => 1,
                    'avancé' => 2,
                ]
            ])
            ->add('isLiked',ChoiceType::class, [
                'label'=>'Voulez vous approfondir cette compétence ? ',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ]
            ])
            ->add('competence',EntityType::class, [
                'class'=>Competence::class,
                'label'=>'Selectionner vos compétences.',
                'label_attr'=>[
                    'class'=>'text-2xl mb-2'
                ],
                'choice_label'=> function (Competence $competence) {
                    return $competence->getNom();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserHasCompetence::class,
        ]);
    }
}
