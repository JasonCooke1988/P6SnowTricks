<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une adresse email',
                    ])
                ]
            ])
            ->add('firstName', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un prénom',
                    ])
                ]
            ])
            ->add('lastName', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom de famille',
                    ])
                ]
            ])
            ->add('username', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom d\'utilisateur',
                    ])
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être de au moins {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
