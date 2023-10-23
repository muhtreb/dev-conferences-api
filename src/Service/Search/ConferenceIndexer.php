<?php

namespace App\Service\Search;

use App\DomainObject\Search\ConferenceDomainObject;
use App\Entity\Conference;
use App\Service\SearchClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ConferenceIndexer
{
    private const INDEX_NAME = 'conferences';

    public function __construct(
        private SearchClient $searchClient,
        private LoggerInterface $logger,
        private NormalizerInterface $normalizer
    )
    {
    }

    public function reset(): void
    {
        $this->searchClient->deleteObjects(static::INDEX_NAME, []);

        $this->searchClient->updateSortableAttributes(static::INDEX_NAME, [
            'name',
        ]);
    }

    public function indexConference(Conference $conference): void
    {
        $dto = $this->getConferenceDTO($conference);

        try {
            $this->searchClient->saveObjects(static::INDEX_NAME, [
                $this->normalizer->normalize($dto)
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function indexConferences(array $conferences): void
    {
        $data = [];
        foreach ($conferences as $conference) {
            $data[] = $this->getConferenceDTO($conference);
        }

        try {
            $this->searchClient->saveObjects(static::INDEX_NAME, $this->normalizer->normalize($data));
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function removeConferenceById(string $conferenceId): void
    {
        try {
            $this->searchClient->deleteObjects(static::INDEX_NAME, [$conferenceId]);
        } catch (\Exception $e) {
        }
    }

    private function getConferenceDTO(Conference $conference): ConferenceDomainObject
    {
        return ConferenceDomainObject::from($conference);
    }
}
