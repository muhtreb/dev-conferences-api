<?php

namespace App\Service\Search\Indexer;

use App\DomainObject\Indexation\ConferenceDomainObject;
use App\Entity\Conference;
use App\Service\Search\Client\SearchClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ConferenceIndexer
{
    private const INDEX_NAME = 'conferences';

    public function __construct(
        private SearchClientInterface $searchClient,
        private LoggerInterface $logger,
        private NormalizerInterface $normalizer,
    ) {
    }

    public function reset(): void
    {
        $this->searchClient->resetIndex(self::INDEX_NAME, [
            'elasticsearch' => [
                'mappings' => [
                    'properties' => [
                        'name' => [
                            'type' => 'text',
                            'fielddata' => true,
                        ],
                    ],
                ],
            ],
        ]);

        $this->searchClient->updateSortableAttributes(self::INDEX_NAME, [
            'name',
        ]);
    }

    public function indexConference(Conference $conference): void
    {
        $dto = $this->getConferenceDTO($conference);

        try {
            $this->searchClient->saveObjects(self::INDEX_NAME, [
                $this->normalizer->normalize($dto),
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
            $this->searchClient->saveObjects(self::INDEX_NAME, $this->normalizer->normalize($data));
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function removeConferenceById(string $conferenceId): void
    {
        try {
            $this->searchClient->deleteObjects(self::INDEX_NAME, [$conferenceId]);
        } catch (\Exception) {
        }
    }

    private function getConferenceDTO(Conference $conference): ConferenceDomainObject
    {
        return ConferenceDomainObject::from($conference);
    }
}
