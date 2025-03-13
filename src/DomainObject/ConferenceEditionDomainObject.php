<?php

namespace App\DomainObject;

use App\Entity\Conference;
use App\Entity\ConferenceEdition;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class ConferenceEditionDomainObject
{
    public ?Uuid $id = null;

    #[Assert\NotBlank(groups: ['create', 'edit'])]
    public string $name;

    public ?string $description = null;

    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[Assert\DateTime(format: 'Y-m-d', groups: ['create', 'edit'])]
    public string $startDate;

    #[Assert\NotBlank(groups: ['create', 'edit'])]
    #[Assert\DateTime(format: 'Y-m-d', groups: ['create', 'edit'])]
    public string $endDate;

    public ?Conference $conference = null;

    public static function from(ConferenceEdition $conferenceEdition): self
    {
        $dto = new self();

        $dto->id = $conferenceEdition->getId();
        $dto->name = $conferenceEdition->getName();
        $dto->description = $conferenceEdition->getDescription();
        $dto->startDate = $conferenceEdition->getStartDate()->format('Y-m-d');
        $dto->endDate = $conferenceEdition->getEndDate()->format('Y-m-d');
        $dto->conference = $conferenceEdition->getConference();

        return $dto;
    }
}
