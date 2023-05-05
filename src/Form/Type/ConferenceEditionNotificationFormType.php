<?php

namespace App\Form\Type;

use App\DomainObject\ConferenceEditionNotificationDomainObject;
use App\Entity\ConferenceEdition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConferenceEditionNotificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'trim' => true,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ]
            ])
            ->add('conferenceEdition', EntityType::class, [
                'required' => true,
                'class' => ConferenceEdition::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConferenceEditionNotificationDomainObject::class,
            'csrf_protection' => false,
            'method' => 'POST',
        ]);
    }
}
