<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom :',
                'attr' => [
                    'class' => 'name-form',
                    'placeholder' => 'votre nom...'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :',
                'attr' => [
                    'class' => 'firstname-form',
                    'placeholder' => 'votre prénom...'
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Email :',
                'attr' => [
                    'class' => 'email-form',
                    'placeholder' => 'votre Email...'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J’accepte les conditions mentionnées ci-dessus et la politique de confidentialité : ',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe'
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de passe : ',
                    'attr' => [
                        'class' => 'password-form',
                        'placeholder' => 'votre mot de passe...'
                        ]
                ],
                'second_options' => [
                    'label' => 'Répéter le mot de passe : ',
                    'attr' => [
                        'class' => 'password-form',
                        'placeholder' => 'répéter votre mot de passe...'
                        ]
                ],
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
