<?php

namespace App\Form;

use App\Entity\Proof;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProofType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('proofFile',FileType::class,[
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "preuve à uploader",
                'label_attr' => [
                'class' => 'text-dark font-weight-bold'
                ],

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'vous devez envoyer un pdf ou un jpg',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proof::class,
        ]);
    }
}
