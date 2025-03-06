<?php

namespace App\Service\Search\Client;

use Algolia\AlgoliaSearch\Api\SearchClient;
use App\DomainObject\Search\SearchQueryDomainObject;
use App\DomainObject\Search\SearchResultsDomainObject;
use App\Enum\SearchClientEnum;

readonly class AlgoliaClient implements SearchClientInterface
{
    private SearchClient $client;

    public function __construct(string $apiKey, string $appId)
    {
        $this->client = SearchClient::create(appId: $appId, apiKey: $apiKey);
    }

    public function supports(SearchClientEnum $searchClientEnum): bool
    {
        return SearchClientEnum::Algolia === $searchClientEnum;
    }

    public function resetIndex(string $indexName, array $options = []): void
    {
        $this->client->replaceAllObjects($indexName, []);
    }

    public function createIndex(string $indexName, array $options = []): void
    {
    }

    public function search(string $indexName, SearchQueryDomainObject $query): SearchResultsDomainObject
    {
        $results = $this->client->searchSingleIndex($indexName, [
            'hitsPerPage' => $query->limit,
            'page' => $query->page - 1,
            'query' => $query->query,
        ]);

        $itemIds = [];
        foreach ($results['hits'] as $hit) {
            $itemIds[] = $hit['objectID'];
        }

        return new SearchResultsDomainObject($itemIds, $results['nbHits']);
    }

    public function saveObjects(string $indexName, $objects): void
    {
        $this->client->saveObjects($indexName, $objects);
    }

    public function deleteObjects(string $indexName, $objectIds): void
    {
        $this->client->replaceAllObjects($indexName, []);
    }

    public function replaceAllObjects(string $indexName, $objects): void
    {
    }

    public function updateSortableAttributes(string $indexName, $sortableAttributes): void
    {
    }

    public function updateFilterableAttributes(string $indexName, $filterableAttributes): void
    {
    }

    public function updateRankingRules(string $indexName, $rankingRules): void
    {
    }
}
