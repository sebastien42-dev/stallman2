<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Civilite;
use App\Entity\Fonction;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civ', EntityType::class, [
                'class' => Civilite::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Civilité",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'libelleCivilite', 
            ])
            ->add('nom',NULL,[
                'attr' => [
                        'class'=> 'form-control border border-dark mb-2'
                    ],
                'label' => "Nom de l'utilisateur",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                    ]
            ])
            ->add('prenom',NULL,[
                'attr' => [
                        'class'=> 'form-control border border-dark mb-2'
                    ],
                'label' => "Prénom de l'utilisateur",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                    ]
            ])
            ->add('fonction', EntityType::class, [
                'class' => Fonction::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Fonction de l'utilisateur",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'libelle_fonction', 
            ])
            ->add('classes', EntityType::class, [
                'class' => Classe::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Fonction de la classe",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'libelle_classe',
                'multiple' => true,
                'by_reference' => false,
                'required' => false
            ])
            ->add('adresse',NULL,[
                'attr' => [
                        'class'=> 'form-control border border-dark mb-2'
                    ],
                'label' => "Adresse de l'utilisateur",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                    ]
            ])
            ->add('societe',NULL,[
                'attr' => [
                        'class'=> 'form-control border border-dark mb-2'
                    ],
                'label' => "Société de l'utilisateur (si formateur)",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                    ]
            ])
            ->add('rib',NULL,[
                'attr' => [
                        'class'=> 'form-control border border-dark mb-2'
                    ],
                'label' => "Rib de l'utilisateur (si formateur)",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                    ]
            ])
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
                'label' => "Rôle de l'utilisateur (cela définit les accessibilités de l'utilisateur)",
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
