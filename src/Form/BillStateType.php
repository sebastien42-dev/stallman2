<?php

namespace App\Form;

use App\Entity\BillState;
use App\Entity\Bill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stateName',NULL,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Nom de l'état de facture",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BillState::class,
        ]);
    }
}
