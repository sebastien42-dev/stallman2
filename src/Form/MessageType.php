<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userFrom',EntityType::class,[
                'class' => User::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "de",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'nom'

            ])
            ->add('UserTo',EntityType::class,[
                'class' => User::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'label' => "pour",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'nom'

            ])
            ->add('title', TextType::class,[
                'attr' => [
                    'class' =>'form-control mb-2 boder border-dark'
                ],
                'label' => 'Titre du message',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('content', TextareaType::class,[
                'attr' => [
                    'class' =>'form-control mb-2 boder border-dark'
                ],
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('isImportant', CheckboxType::class,[
                'label' => 'flag important ->  ',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'required' => false
            ])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
