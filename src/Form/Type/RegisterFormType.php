<?php

namespace App\Form\Type;

use App\DomainObject\RegisterDomainObject;
use App\Entity\User;
use App\Validator\Constraints\UniqueValueInEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new UniqueValueInEntity([
                        'entityClass' => User::class,
                        'field' => 'email',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    // new NotCompromisedPassword(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegisterDomainObject::class,
            'csrf_protection' => false,
            'method' => 'POST',
        ]);
    }
}
