<?php

namespace App\Form\Type;

use App\DomainObject\SpeakerDomainObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SpeakerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('xUsername', TextType::class, [
                'required' => false,
            ])
            ->add('githubUsername', TextType::class, [
                'required' => false,
            ])
            ->add('mastodonUsername', TextType::class, [
                'required' => false,
            ])
            ->add('blueskyUsername', TextType::class, [
                'required' => false,
            ])
            ->add('speakerDeckUsername', TextType::class, [
                'required' => false,
            ])
            ->add('website', TextType::class, [
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpeakerDomainObject::class,
            'csrf_protection' => false,
            'method' => 'POST',
        ]);
    }
}
