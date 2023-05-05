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
    public ?string $github = null;
    public ?string $twitter = null;

    public static function from(Speaker $speaker): self
    {
        $dto = new self();

        $dto->id = $speaker->getId();
        $dto->firstName = $speaker->getFirstName();
        $dto->lastName = $speaker->getLastName();
        $dto->github = $speaker->getGithub();
        $dto->twitter = $speaker->getTwitter();
        $dto->description = $speaker->getDescription();

        return $dto;
    }
}
