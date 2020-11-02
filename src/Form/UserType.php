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
                'attr' => 
                    ['class'=> 'form-control',
                    'placeholder' => 'dupont.camille@quelquechose.fr'
                ],
                'label' => "Mail de l'utilisateur",
                'label_attr' => ['class' => 'text-white font-weight-bold']
            ])
            ->add('roles', ChoiceType::class, [
                'attr'  =>  
                    ['class' => 'form-control'
                ],
                'label' => "Rôle de l'utilisateur (il peut y en avoir plusieurs)",
                'label_attr' => ['class' => 'text-white font-weight-bold mt-3'],
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Professeur' => 'ROLE_PROF',
                    'Elève' => 'ROLE_ELEVE'
                ],
                'multiple' => true,
                'required' => true,
            ])
            ->add('password', PasswordType::class,[
                'attr' =>  
                ['class' => 'form-control',
                'placeholder' => 'la personne devra le changer dans un second temps'
            ],
                'label' => "Mot de passe initial",
                'label_attr' => ['class' => 'text-white font-weight-bold mt-3']
               
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
