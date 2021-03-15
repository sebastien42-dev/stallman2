<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Salle;
use App\Entity\Classe;
use App\Entity\Matiere;
use App\Entity\EventPlanning;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventPlanningType extends AbstractType
{//TODO defaut dans l'affichage de la date et surtout faire un ajax pour que les champs prof s'adapte en fonction de la classe ou la matiere
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => [
                    'class' =>'form-control mb-2 boder border-dark'
                ],
                'label' => 'Titre (facultatif, par défaut le nom de la salle, du formateur et la classe)',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('start', DateTimeType::class,[
                'attr' => [
                    'class' =>'form-control mb-5 boder border-dark'
                ],
                'label' => 'Date et heure de début de cours',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'date_widget' => 'single_text',
                'hours'=>range(8,19)
            ])
            ->add('end', DateTimeType::class,[
                'attr' => [
                    'class' =>'form-control mb-5 boder border-dark'
                ],
                'label' => 'Date et heure de fin de cours',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'date_widget' => 'single_text',
                'hours'=>range(8,19)
            ])
            ->add('isDistance', CheckboxType::class,[
                    'label' => 'cours à distance ->  ',
                    'label_attr' => [
                        'class' => 'text-dark font-weight-bold'
                    ],
            ])
            ->add('salles', EntityType::class, [
                'class' => Salle::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Salle du cours",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'libelleSalle', 
            ])
            ->add('classes', EntityType::class, [
                'class' => Classe::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Classe concernée par le cours",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'libelleClasse', 
            ])
            ->add('matieres', EntityType::class, [
                'class' => Matiere::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Matière concernée par le cours",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'libelleMatiere', 
            ])
            ->add('formateur',EntityType::class, [
                'attr' => [
                    'class' =>'form-control boder border-dark mb-2'
                ],
                'label' => 'choisir le(s) formateurs',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'class' => User::class,
                //permet de ne recuperer que les formateurs
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.fonction=2')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'multiple' => false,
                'by_reference' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventPlanning::class,
        ]);
    }
}
