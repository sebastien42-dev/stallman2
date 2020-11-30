<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\User;
use App\Entity\Matiere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelleNote', TextType::class,[
                'attr' => [
                    'class' =>'form-control mb-2 boder border-dark'
                ],
                'label' => 'Intitulé de l\'élément noté',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('dateNote', DateType::class,[
                'attr' => [
                    'class' =>'form-control mb-5 boder border-dark'
                ],
                'label' => 'Date de la note',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'widget' => 'single_text'
            ])
            ->add('commentaire', TextareaType::class,[
                'attr' => [
                    'class' =>'form-control mb-2 boder border-dark'
                ],
                'label' => 'Commentaire sur la note',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('note', NumberType::class,[
                'attr' => [
                    'class' =>'form-control mb-2 boder border-dark'
                ],
                'label' => 'Note (sur 20)',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('coefficient', NumberType::class,[
                'attr' => [
                    'class' =>'form-control mb-2 boder border-dark'
                ],
                'label' => 'Coefficient à appliquer sur la note',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('matieres', EntityType::class, [
                'class' => Matiere::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "Matière concernée par la note",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'libelleMatiere', 
            ])
            ->add('eleves', EntityType::class, [
                'class' => User::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "élève concerné par la note",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'nom', 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
