<?php

namespace App\DomainObject\Indexation;

use App\DomainObject\Search\SearchDomainObject;
use App\Entity\Speaker;

class SpeakerDomainObject extends SearchDomainObject
{
    public string $firstName;
    public string $lastName;
    public ?string $description = null;
    public ?string $githubUsername = null;
    public ?string $xUsername = null;
    public ?string $speakerDeckUsername = null;
    public ?string $mastodonUsername = null;
    public ?string $blueskyUsername = null;
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
        $dto->speakerDeckUsername = $speaker->getSpeakerDeckUsername();
        $dto->mastodonUsername = $speaker->getMastodonUsername();
        $dto->blueskyUsername = $speaker->getBlueskyUsername();

        return $dto;
    }
}
