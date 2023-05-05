<?php

namespace App\Manager\Admin;

use App\DomainObject\SpeakerDomainObject;
use App\Entity\Speaker;
use App\Repository\SpeakerRepository;
use App\Service\Search\SpeakerIndexer;

readonly class SpeakerManager
{
    public function __construct(
        private SpeakerRepository $speakerRepository,
        private SpeakerIndexer $speakerIndexer,
    ) {
    }

    public function createSpeakerFromDTO(SpeakerDomainObject $dto): Speaker
    {
        $speaker = (new Speaker())
            ->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setTwitter($dto->twitter)
            ->setGithub($dto->github)
            ->setDescription($dto->description);

        $this->speakerRepository->save($speaker);

        $this->speakerIndexer->indexSpeaker($speaker);

        return $speaker;
    }

    public function updateSpeakerFromDTO(Speaker $speaker, SpeakerDomainObject $dto): Speaker
    {
        $speaker
            ->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setTwitter($dto->twitter)
            ->setGithub($dto->github)
            ->setDescription($dto->description);

        $this->speakerRepository->save($speaker);

        $this->speakerIndexer->indexSpeaker($speaker);

        return $speaker;
    }

    public function removeSpeaker(Speaker $speaker): void
    {
        $id = $speaker->getId();

        $this->speakerRepository->remove($speaker);

        $this->speakerIndexer->removeSpeakerById($id);
    }
}
