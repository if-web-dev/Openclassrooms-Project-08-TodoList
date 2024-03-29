<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'row_attr' => [
                    'class' => 'form-floating mb-4',
                ],
                'attr' => [
                    'placeholder' => 'Username'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'both passwords must match',
                'required' => true,
                
                'first_options'  => [
                    'label' => 'Password',
                    'row_attr' => [
                        'class' => 'form-floating mb-4',
                    ],
                    'attr' => [
                        'placeholder' => 'Password'
                    ]
                ],
                'second_options' => [
                    'label' => 'Enter the password again',
                    'row_attr' => [
                        'class' => 'form-floating mb-4',
                    ],
                    'attr' => [
                        'placeholder' => 'Enter the password again'
                    ]
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email adress',
                'row_attr' => [
                    'class' => 'form-floating mb-4',
                ],
                'attr' => [
                    'placeholder' => 'Email adress'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => "ROLE_USER",
                    'Admin' => "ROLE_ADMIN",
                ],
                'placeholder' => '--Choose a role--',
                'row_attr' => [
                    'class' => 'form-floating mb-4',
                ],
            ]);

        $builder
            ->get('roles')
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($rolesAsArray) {
                        return count($rolesAsArray) ? $rolesAsArray[0] : null;
                    },
                    function ($rolesAsString) {
                        return [$rolesAsString];
                    }
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
