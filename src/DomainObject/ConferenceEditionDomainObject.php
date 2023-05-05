<?php

namespace App\DomainObject;

use App\Entity\Conference;
use App\Entity\ConferenceEdition;
use Symfony\Component\Uid\Uuid;

class ConferenceEditionDomainObject
{
    public ?Uuid $id = null;
    public string $name;
    public ?string $description = null;
    public ?\DateTime $startDate = null;
    public ?\DateTime $endDate = null;
    public ?Conference $conference = null;

    public static function from(ConferenceEdition $conferenceEdition): self
    {
        $dto = new self();

        $dto->id = $conferenceEdition->getId();
        $dto->name = $conferenceEdition->getName();
        $dto->description = $conferenceEdition->getDescription();
        $dto->startDate = $conferenceEdition->getStartDate();
        $dto->endDate = $conferenceEdition->getEndDate();
        $dto->conference = $conferenceEdition->getConference();

        return $dto;
    }
}
