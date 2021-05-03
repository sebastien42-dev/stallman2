<?php

namespace App\Form;

use App\Entity\Bill;
use App\Form\BillLignType;
use App\Form\BillLignForBillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('billProviderNum',NULL,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Numéro de la facture fournisseur",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add("billLigns",CollectionType::class,[
                "entry_type" => BillLignForBillType::class,
                "entry_options" => [
                    "label" => false, 
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ])
            //->add('createdAt')
            //->add('updatedAt')
            // ->add('globalBillValue',NULL,[
            //     'attr' => [
            //         'class'=> 'form-control border border-dark mb-2'
            //     ],
            //     'label' => "Montant Global de la facture",
            //     'label_attr' => [
            //     'class' => 'text-dark font-weight-bold'
            //     ],
            //     "disabled" => true
            // ])
            //->add('user')
            //->add('billState')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bill::class,
        ]);
    }
}
