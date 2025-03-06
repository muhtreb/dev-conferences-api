<?php

namespace App\DomainObject\Indexation;

use App\DomainObject\Search\SearchDomainObject;
use App\Entity\Conference;

class ConferenceDomainObject extends SearchDomainObject
{
    public string $name;
    public ?string $description = null;

    public static function from(Conference $conference): self
    {
        $dto = new self();
        $dto->objectID = $conference->getId();
        $dto->name = $conference->getName();
        $dto->description = $conference->getDescription();

        return $dto;
    }
}
