<?php

namespace App\Manager\Admin;

use App\DomainObject\SpeakerDomainObject;
use App\Entity\Speaker;
use App\Repository\SpeakerRepository;
use App\Service\Search\SpeakerIndexer;
use App\Service\SpeakerSlugGenerator;

readonly class SpeakerManager
{
    public function __construct(
        private SpeakerRepository $speakerRepository,
        private SpeakerIndexer $speakerIndexer,
        private SpeakerSlugGenerator $speakerSlugGenerator,
    ) {
    }

    public function createSpeakerFromDTO(SpeakerDomainObject $dto): Speaker
    {
        $speaker = new Speaker();

        $this->populateSpeakerFromDTO($speaker, $dto);

        $this->speakerRepository->save($speaker);

        $this->speakerIndexer->indexSpeaker($speaker);

        return $speaker;
    }

    public function updateSpeakerFromDTO(Speaker $speaker, SpeakerDomainObject $dto): Speaker
    {
        $this->populateSpeakerFromDTO($speaker, $dto);

        $this->speakerRepository->save($speaker);

        $this->speakerIndexer->indexSpeaker($speaker);

        return $speaker;
    }

    private function populateSpeakerFromDTO(Speaker $speaker, SpeakerDomainObject $dto): void
    {
        $speaker
            ->setFirstName($dto->firstName)
            ->setLastName($dto->lastName)
            ->setSlug($this->speakerSlugGenerator->generateSlug($dto->firstName . ' ' . $dto->lastName, $speaker))
            ->setXUsername($dto->xUsername)
            ->setSpeakerDeckUsername($dto->speakerDeckUsername)
            ->setMastodonUsername($dto->mastodonUsername)
            ->setBlueskyUsername($dto->blueskyUsername)
            ->setGithubUsername($dto->githubUsername)
            ->setWebsite($dto->website)
            ->setDescription($dto->description);
    }

    public function removeSpeaker(Speaker $speaker): void
    {
        $id = $speaker->getId();

        $this->speakerRepository->remove($speaker);

        $this->speakerIndexer->removeSpeakerById($id);
    }
}
