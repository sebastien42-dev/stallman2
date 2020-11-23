<?php

namespace App\Form;

use App\Entity\Classe;
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
            // ->add('matieres')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
