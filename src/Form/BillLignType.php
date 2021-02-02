<?php

namespace App\Form;

use App\Entity\Package;
use App\Entity\BillLign;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BillLignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity',IntegerType::class,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Quantité",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('package', EntityType::class,[
                'class' => Package::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "forfait",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ],
                "choice_label" => "packageName"
            ])
            // ->add('globalLignValue',NULL,[
            //     'attr' => [
            //         'class'=> 'form-control border border-dark mb-2'
            //     ],
            //     'label' => "Nom de l'état de facture",
            //     'label_attr' => [
            //     'class' => 'text-dark font-weight-bold'
            //     ]
            // ])
            ->add('createdAt',DateType::class,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Date concernée par le frais",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ],
                "widget" => "single_text"
            ])
            //->add('bill')
            //->add('package')
            //->add('outPackage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BillLign::class,
        ]);
    }
}
