<?php

namespace App\Form;

use App\Entity\EventPlanning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventPlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('start')
            ->add('end')
            ->add('salles')
            ->add('classes')
            ->add('matieres')
            ->add('formateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventPlanning::class,
        ]);
    }
}
