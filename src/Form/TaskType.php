<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\NotNull()
                ],
            ])
            ->add('content', TextareaType::class)
            ->add('checkbox_field', CheckboxType::class, [
                'label' => 'Create a deadline ?',
                'required' => false,
                'mapped' => false,
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => false,
                'row_attr' => [
                    'class' => 'mb-3 d-none',
                    'id' => 'conditional-field'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'allow_extra_fields' => true
        ]);
    }
}
