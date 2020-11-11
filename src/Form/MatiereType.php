<?php

namespace App\Form;

use App\Entity\Matiere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle_matiere', TextType::class,[
                'attr' => [
                    'class' =>'form-control'
                ],
                'label' => 'Nom de la matière',
                'label_attr' => [
                    'class' => 'text-white font-weight-bold'
                ]
            ])
            //TODO verifier une saisie positive et un défaut à 1
            ->add('coefficient',IntegerType::class,[
                'attr' => [
                    'class' =>'form-control'
                ],
                'label' => 'Coefficient de la matière',
                'label_attr' => [
                    'class' => 'text-white font-weight-bold'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}
