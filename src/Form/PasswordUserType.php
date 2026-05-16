<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword',PasswordType::class,[
                'label' => 'Saisir votre Mot de passe actuel', 
                    'attr' =>[
                        'placeholder' => 'Saisir votre Mot de passe actuel'
                    ], 
                    'mapped' => false
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length(
                        min : 4 ,
                        max : 30
                    )
                ],
                'first_options'  => [
                    'label' => 'Saisir votre nouveau Mot de passe', 
                    'attr' =>[
                        'placeholder' => 'Saisir votre nouveau Mot de passe'
                    ],
                    'hash_property_path' => 'password'
                    ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' =>[
                        'placeholder' => 'Confirmez votre nouveau Mot de passe'
                    ]
                    ],
                'mapped' => false,
            ])

           ->add('submit',SubmitType::class,[
                'label' => 'Changer Password',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT,function(FormEvent $event){
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
               
                // Récupérer le mot de passe saisie & le comparer 
                $actuelPwd = $form->get('actualPassword')->getData();
                 $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $actuelPwd
                );
                // envoyer erreur en cas false
                if(!$isValid){
                    $form->get('actualPassword')->addError(new FormError("votre mot de passe saisie n'est pas correct"));
                }





            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
