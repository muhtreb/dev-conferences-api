<?php

namespace App\Service\Search;

use App\DomainObject\Search\SpeakerDomainObject;
use App\Entity\Speaker;
use App\Repository\TalkRepository;
use App\Service\SearchClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class SpeakerIndexer
{
    private const INDEX_NAME = 'speakers';

    public function __construct(
        private SearchClient $searchClient,
        private LoggerInterface $logger,
        private NormalizerInterface $normalizer,
        private TalkRepository $talkRepository,
    ) {
    }

    public function reset(): void
    {
        $this->searchClient->reset(self::INDEX_NAME);

        $this->searchClient->updateSortableAttributes(self::INDEX_NAME, [
            'countTalks',
        ]);
    }

    public function indexSpeaker(Speaker $speaker): void
    {
        $dto = $this->getSpeakerDTO($speaker);

        try {
            $this->searchClient->saveObjects(self::INDEX_NAME, [
                $this->normalizer->normalize($dto),
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function indexSpeakers(array $speakers): void
    {
        $data = [];
        foreach ($speakers as $speaker) {
            $data[] = $this->getSpeakerDTO($speaker);
        }

        try {
            $this->searchClient->saveObjects(self::INDEX_NAME, $this->normalizer->normalize($data));
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function removeSpeakerById(string $id): void
    {
        try {
            $this->searchClient->deleteObjects(self::INDEX_NAME, [$id]);
        } catch (\Exception $e) {
        }
    }

    private function getSpeakerDTO(Speaker $speaker): SpeakerDomainObject
    {
        $dto = SpeakerDomainObject::from($speaker);
        $dto->countTalks = $this->talkRepository->countSpeakerTalks($speaker);

        return $dto;
    }
}
