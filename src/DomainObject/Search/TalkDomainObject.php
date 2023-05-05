<?php

namespace App\DomainObject\Search;

use App\Entity\SpeakerTalk;
use App\Entity\Talk;

class TalkDomainObject extends SearchDomainObject
{
    public string $name;
    public ?string $description;
    public string $date;
    public array $speakers;
    public string $editionName;

    public static function from(Talk $talk): self
    {
        $dto = new self();
        $dto->objectID = $talk->getId();
        $dto->name = $talk->getName();
        $dto->description = $talk->getDescription();
        $dto->date = $talk->getDate()->format('Y-m-d H:i:s');
        $dto->speakers = $talk
            ->getSpeakers()
            ->map(fn(SpeakerTalk $speakerTalk) => $speakerTalk->getSpeaker()->getFirstName() . ' ' . $speakerTalk->getSpeaker()->getLastName())
            ->toArray();
        $dto->editionName = $talk->getConferenceEdition()->getName();

        return $dto;
    }
}
