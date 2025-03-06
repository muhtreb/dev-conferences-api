<?php

namespace App\DomainObject;

use App\Entity\Tag;
use App\Validator\Constraints\UniqueValueInEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class TagDomainObject
{
    public ?Uuid $id = null;
    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[UniqueValueInEntity(
        entityClass: Tag::class,
        field: 'name',
        groups: ['create']
    )]
    public string $name;

    public static function from(Tag $tag): self
    {
        $dto = new self();
        $dto->id = $tag->getId();
        $dto->name = $tag->getName();

        return $dto;
    }
}
