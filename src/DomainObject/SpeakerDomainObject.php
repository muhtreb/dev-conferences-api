<?php

namespace App\DomainObject;

use App\Entity\Speaker;
use Symfony\Component\Uid\Uuid;

class SpeakerDomainObject
{
    public ?Uuid $id = null;
    public string $firstName;
    public string $lastName;
    public ?string $description = null;
    public ?string $githubUsername = null;
    public ?string $xUsername = null;
    public ?string $speakerDeckUsername = null;
    public ?string $mastodonUsername = null;
    public ?string $blueskyUsername = null;
    public ?string $website = null;

    public static function from(Speaker $speaker): self
    {
        $dto = new self();

        $dto->id = $speaker->getId();
        $dto->firstName = $speaker->getFirstName();
        $dto->lastName = $speaker->getLastName();
        $dto->githubUsername = $speaker->getGithubUsername();
        $dto->xUsername = $speaker->getXUsername();
        $dto->speakerDeckUsername = $speaker->getSpeakerDeckUsername();
        $dto->mastodonUsername = $speaker->getMastodonUsername();
        $dto->blueskyUsername = $speaker->getBlueskyUsername();
        $dto->website = $speaker->getWebsite();
        $dto->description = $speaker->getDescription();

        return $dto;
    }
}
