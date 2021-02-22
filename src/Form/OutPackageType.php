<?php

namespace App\Form;

use App\Entity\OutPackage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutPackageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('outPackageName',NULL,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Libelle du hors forfait",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('value',IntegerType::class,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Montant du hors forfait",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ]
            ])
            //TODO rajouter le lien pour upload des preuves pour le hors forfait
            //->add('proof')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OutPackage::class,
        ]);
    }
}
