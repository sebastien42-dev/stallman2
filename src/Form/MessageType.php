<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Message;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    private $security;

    function __construct(Security $security)
    {
        $this->setSecurity($security);
    }
    
    /**
     * Get the value of security
     */ 
    public function getSecurity()
    {
        return $this->security;
    }

    /**
     * Set the value of security
     *
     * @return  self
     */ 
    public function setSecurity($security)
    {
        $this->security = $security;

        return $this;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder   
            ->add('UserTo',EntityType::class,[
                'class' => User::class,
                'attr' => [
                    'class'=> 'form-control border border-dark mb-2'
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.id != :connecteduser')
                        ->setParameter('connecteduser', $this->security->getUser()->getId())
                        ->orderBy('u.nom', 'ASC');
                },
                'label' => "Destinataire",
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ],
                'choice_label' => 'fullName',
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
                    'class' =>'form-control mb-2 boder border-dark',
                    'rows' => 9
                ],
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'text-dark font-weight-bold'
                ]
            ])
            ->add('isImportant', CheckboxType::class,[
                'label' => ' ',
                'label_attr' => [
                    'class' => 'fas fa-flag text-danger mr-4'
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
