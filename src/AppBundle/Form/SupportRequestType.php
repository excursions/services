<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SupportRequestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Name',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Name (Business Or Personal)',
                        'class' => 'form-control'
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Email',
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Description',
                    'attr' => ['class' => 'form-control js-description'],
                ]
            )
            ->add(
                'subject',
                HiddenType::class,
                [
                    'required' => false,
                    'attr' => ['class' => 'js-subject'],
                ]
            );
    }
}
