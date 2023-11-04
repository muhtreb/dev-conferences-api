<?php

namespace App\Service\Search;

use App\DomainObject\Search\ConferenceEditionDomainObject;
use App\Entity\ConferenceEdition;
use App\Service\SearchClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ConferenceEditionIndexer
{
    private const INDEX_NAME = 'conference_editions';

    public function __construct(
        private SearchClient $searchClient,
        private LoggerInterface $logger,
        private NormalizerInterface $normalizer
    ) {
    }

    public function reset(): void
    {
        $this->searchClient->reset(static::INDEX_NAME);

        $this->searchClient->updateSortableAttributes(static::INDEX_NAME, [
            'date',
        ]);
    }

    public function indexConferenceEdition(ConferenceEdition $conferenceEdition): void
    {
        $dto = ConferenceEditionDomainObject::from($conferenceEdition);

        try {
            $this->searchClient->saveObjects(static::INDEX_NAME, [
                $this->normalizer->normalize($dto)
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
            $this->searchClient->saveObjects(static::INDEX_NAME, $this->normalizer->normalize($data));
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
}
