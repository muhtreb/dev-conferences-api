<?php

namespace App\DomainObject;

use App\Entity\Tag;
use Symfony\Component\Uid\Uuid;

class TagDomainObject
{
    public ?Uuid $id = null;
    public string $name;

    public static function from(Tag $tag): self
    {
        $dto = new self();
        $dto->id = $tag->getId();
        $dto->name = $tag->getName();

        return $dto;
    }
}
