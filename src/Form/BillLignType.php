<?php

namespace App\Form;

use App\Entity\BillLign;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillLignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('globalLignValue')
            ->add('createdAt')
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
