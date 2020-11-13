<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'attr' => [
                        'class'=> 'form-control border border-dark mb-2'
                    ],
                'label' => "Mail de l'utilisateur",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                    ]
            ])
            ->add('roles', ChoiceType::class, [
                'attr'  =>  
                    ['class' => 'form-control border border-dark mb-2'
                ],
                'label' => "Rôle de l'utilisateur (il peut y en avoir plusieurs)",
                'label_attr' => ['class' => 'text-dark font-weight-bold mb-2'],
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Professeur' => 'ROLE_PROF',
                    'Elève' => 'ROLE_ELEVE',
                    'Comptabilité' => 'ROLE_COMPTA'
                ],
                'multiple' => true,
                'required' => true,
            ])
            ->add('password', PasswordType::class,[
                'attr' =>  
                ['class' => 'form-control border border-dark'
            ],
                'label' => "Mot de passe",
                'label_attr' => ['class' => 'text-dark font-weight-bold mb-2']
               
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
