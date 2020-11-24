<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Matiere;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle_matiere', TextType::class,[
                'attr' => [
                    'class' =>'form-control mb-2 boder border-dark'
                ],
                'label' => 'Nom de la matière',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            //TODO verifier une saisie positive et un défaut à 1
            ->add('coefficient',IntegerType::class,[
                'attr' => [
                    'class' =>'form-control boder border-dark mb-2'
                ],
                'label' => 'Coefficient de la matière',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('users',EntityType::class, [
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
                'multiple' => true,
                'by_reference' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}
