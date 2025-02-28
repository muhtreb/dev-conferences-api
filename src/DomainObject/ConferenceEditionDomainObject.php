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
    public ?\DateTimeInterface $startDate = null;
    public ?\DateTimeInterface $endDate = null;
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
