<?php

namespace App\Form\Type;

use App\DomainObject\YoutubePlaylistImportDomainObject;
use App\Entity\YoutubePlaylistImport;
use App\Validator\Constraints\UniqueValueInEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YoutubePlaylistImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('playlistId', TextType::class, [
                'required' => true,
                'constraints' => [
                    new UniqueValueInEntity([
                        'field' => 'playlistId',
                        'entityClass' => YoutubePlaylistImport::class,
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => YoutubePlaylistImportDomainObject::class,
            'csrf_protection' => false,
            'method' => 'POST',
        ]);
    }
}