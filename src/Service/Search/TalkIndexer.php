<?php

namespace App\Service\Search;

use App\DomainObject\Search\TalkDomainObject;
use App\Entity\Talk;
use App\Service\SearchClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class TalkIndexer
{
    private const INDEX_NAME = 'talks';

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

    public function indexTalk(Talk $talk): void
    {
        $dto = TalkDomainObject::from($talk);

        try {
            $this->searchClient->saveObjects(static::INDEX_NAME, [
                $this->normalizer->normalize($dto)
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }

    public function removeTalkById(string $id): void
    {
        try {
            $this->searchClient->deleteObjects(static::INDEX_NAME, [$id]);
        } catch (\Exception $e) {
        }
    }


    public function indexTalks(array $talks): void
    {
        $data = [];
        foreach ($talks as $talk) {
            $data[] = TalkDomainObject::from($talk);
        }

        try {
            $this->searchClient->saveObjects(static::INDEX_NAME, $this->normalizer->normalize($data));
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
}
