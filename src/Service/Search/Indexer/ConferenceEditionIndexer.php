<?php

namespace App\Service\Search\Indexer;

use App\DomainObject\Indexation\ConferenceEditionDomainObject;
use App\Entity\ConferenceEdition;
use App\Service\Search\Client\SearchClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ConferenceEditionIndexer
{
    private const INDEX_NAME = 'conference_editions';

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
            'date',
        ]);
    }

    public function indexConferenceEdition(ConferenceEdition $conferenceEdition): void
    {
        $dto = ConferenceEditionDomainObject::from($conferenceEdition);

        try {
            $this->searchClient->saveObjects(self::INDEX_NAME, [
                $this->normalizer->normalize($dto),
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function indexConferenceEditions(array $conferenceEditions): void
    {
        $data = [];
        foreach ($conferenceEditions as $conferenceEdition) {
            $data[] = ConferenceEditionDomainObject::from($conferenceEdition);
        }

        try {
            $this->searchClient->saveObjects(self::INDEX_NAME, $this->normalizer->normalize($data));
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function removeConferenceEditionById(string $conferenceEditionId): void
    {
        try {
            $this->searchClient->deleteObjects(self::INDEX_NAME, [$conferenceEditionId]);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
}
