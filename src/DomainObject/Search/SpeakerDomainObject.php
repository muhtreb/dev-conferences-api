<?php

namespace App\DomainObject\Search;

use App\Entity\Speaker;

class SpeakerDomainObject extends SearchDomainObject
{
    public string $firstName;
    public string $lastName;
    public ?string $description;
    public ?string $githubUsername ;
    public ?string $xUsername;
    public int $countTalks = 0;

    public static function from(Speaker $speaker): self
    {
        $dto = new self();
        $dto->objectID = $speaker->getId();
        $dto->firstName = $speaker->getFirstName();
        $dto->lastName = $speaker->getLastName();
        $dto->description = $speaker->getDescription();
        $dto->githubUsername = $speaker->getGithubUsername();
        $dto->xUsername = $speaker->getXUsername();

        return $dto;
    }
}
