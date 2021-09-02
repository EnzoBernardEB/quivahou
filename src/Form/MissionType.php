<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\MissionEnCours;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description',)
            ->add('entreprise',EntityType::class, [
                'class'=>Entreprise::class,
                'label'=>'Selectionner l\'entreprise :',
                'label_attr'=>[
                    'class'=>'text-2xl mb-2'
                ],
                'choice_label'=> function (Entreprise $entreprise) {
                    return $entreprise->getNom();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MissionEnCours::class,
        ]);
    }
}
