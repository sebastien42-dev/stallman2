<?php

namespace App\Form;

use App\Entity\Package;
use App\Entity\BillLign;
use App\Form\OutPackageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BillLignForBillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity',IntegerType::class,[
                'attr' => [
                    'class'=> 'form-control mb-1 mt-5 col-12 text-success border border-success',
                    'placeholder' => "quantité",
                    "style" => "font-size:12px;"
                ],
                'label' => false,
            ])
            ->add('package', EntityType::class,[
                'class' => Package::class,
                'attr' => [
                    'class'=> 'form-control mb-1 col-12 text-success border border-success',
                    'placeholder' => "forfait",
                    "style" => "font-size:12px;"
                ],
                'label' => false,
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
                    'class'=> 'form-control mb-4 col-12 text-success border border-success',
                    "style" => "font-size:12px;"
                ],
                'label' => false,
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
