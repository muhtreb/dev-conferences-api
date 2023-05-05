<?php

namespace App\DomainObject;

use App\Entity\ConferenceEdition;
use App\Entity\SpeakerTalk;
use App\Entity\Talk;

class TalkDomainObject
{
    public string $name;
    public ?string $description = null;
    public \DateTimeInterface $date;
    public ConferenceEdition $conferenceEdition;
    public string $youtubeId;
    public ?string $speakerIds = null;

    public static function from(Talk $talk): self
    {
        $dto = new self();

        $dto->id = $talk->getId();
        $dto->name = $talk->getName();
        $dto->description = $talk->getDescription();
        $dto->date = $talk->getDate();
        $dto->conferenceEdition = $talk->getConferenceEdition();
        $dto->youtubeId = $talk->getYoutubeId();
        $dto->speakerIds = implode(',', array_map(fn(SpeakerTalk $speakerTalk): string => $speakerTalk->getSpeaker()->getId(), $talk->getSpeakers()->toArray()));

        return $dto;
    }
}
