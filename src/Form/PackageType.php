<?php

namespace App\Form;

use App\Entity\Package;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('packageName',NULL,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Libelle du forfait",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('value',NULL,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Montant unitaire",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Package::class,
        ]);
    }
}
