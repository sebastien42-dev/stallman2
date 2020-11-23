<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelleClasse',NULL,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
            'label' => "Nom de la classe",
            'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ]
            ])
            // ->add('users')
            ->add('matieres',EntityType::class,[
                'class' => Matiere::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Matières de la classe",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'libelle_matiere',
                'multiple' => true,
                'by_reference' => false

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
