<?php

namespace App\Service\Search\Client;

use App\DomainObject\Search\SearchQueryDomainObject;
use App\DomainObject\Search\SearchResultsDomainObject;
use App\Enum\SearchClientEnum;
use Meilisearch\Client;

readonly class MeilisearchClient implements SearchClientInterface
{
    private Client $client;

    public function __construct(string $url, string $apiKey)
    {
        $this->client = new Client($url, $apiKey);
    }

    public function supports(SearchClientEnum $searchClientEnum): bool
    {
        return SearchClientEnum::Meilisearch === $searchClientEnum;
    }

    public function createIndex(string $indexName, array $options = []): void
    {
        $this->client->createIndex($indexName, $options);
    }

    public function resetIndex(string $indexName, array $options = []): void
    {
        $index = $this->client->index($indexName);
        $index->deleteAllDocuments();
    }

    public function search(string $indexName, SearchQueryDomainObject $query): SearchResultsDomainObject
    {
        $index = $this->client->index($indexName);

        $result = $index->search($query->query, [
            'attributesToRetrieve' => [
                'objectID',
            ],
            'hitsPerPage' => $query->limit,
            'page' => $query->page,
            'sort' => [
                $query->sort->field.':'.$query->sort->direction,
            ],
        ])->toArray();

        return new SearchResultsDomainObject(
            array_map(fn ($hit) => $hit['objectID'], $result['hits']),
            $result['totalHits']
        );
    }

    public function saveObjects(string $indexName, $objects): void
    {
        $index = $this->client->index($indexName);
        $index->addDocuments($objects);
    }

    public function deleteObjects(string $indexName, $objectIds): void
    {
        $index = $this->client->index($indexName);
        $index->deleteDocuments($objectIds);
    }

    public function replaceAllObjects(string $indexName, $objects): void
    {
        $index = $this->client->index($indexName);
        $index->updateDocuments($objects);
    }

    public function updateSortableAttributes(string $indexName, $sortableAttributes): void
    {
        $index = $this->client->index($indexName);
        $index->updateSortableAttributes($sortableAttributes);
    }

    public function updateFilterableAttributes(string $indexName, $filterableAttributes): void
    {
        $index = $this->client->index($indexName);
        $index->updateFilterableAttributes($filterableAttributes);
    }

    public function updateRankingRules(string $indexName, $rankingRules): void
    {
        $index = $this->client->index($indexName);
        $index->updateRankingRules($rankingRules);
    }
}
