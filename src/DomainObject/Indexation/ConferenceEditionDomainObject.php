<?php

namespace App\DomainObject\Indexation;

use App\DomainObject\Search\SearchDomainObject;
use App\Entity\ConferenceEdition;

class ConferenceEditionDomainObject extends SearchDomainObject
{
    public string $name;
    public ?string $description = null;
    public ?string $date = null;

    public static function from(ConferenceEdition $conferenceEdition): self
    {
        $dto = new self();
        $dto->objectID = $conferenceEdition->getId();
        $dto->name = $conferenceEdition->getName();
        $dto->description = $conferenceEdition->getDescription();
        $dto->date = $conferenceEdition->getStartDate() ? $conferenceEdition->getStartDate()->format('Y-m-d') : null;

        return $dto;
    }
}
