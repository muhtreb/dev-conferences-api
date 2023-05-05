<?php

namespace App\Form\Type;

use App\DomainObject\ConferenceDomainObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConferenceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('website', TextType::class, [
                'required' => false,
            ])
            ->add('twitter', TextType::class, [
                'required' => false,
            ])
            ->add('thumbnailImageUrl', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConferenceDomainObject::class,
            'csrf_protection' => false,
            'method' => 'POST',
        ]);
    }
}