<?php

namespace App\DomainObject\Search;

use App\Entity\ConferenceEdition;

class ConferenceEditionDomainObject extends SearchDomainObject
{
    public string $name;
    public ?string $description;
    public ?string $date;

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
