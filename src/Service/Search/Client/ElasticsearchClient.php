<?php

namespace App\Service\Search\Client;

use App\DomainObject\Search\SearchQueryDomainObject;
use App\DomainObject\Search\SearchResultsDomainObject;
use App\Enum\SearchClientEnum;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

readonly class ElasticsearchClient implements SearchClientInterface
{
    private Client $client;

    public function supports(SearchClientEnum $searchClientEnum): bool
    {
        return SearchClientEnum::Elasticsearch === $searchClientEnum;
    }

    public function __construct(array $hosts)
    {
        $this->client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    }

    public function resetIndex(string $indexName, array $options = []): void
    {
        try {
            $this->client->indices()->delete(['index' => $indexName]);
        } catch (\Exception $e) {
        }

        $this->createIndex($indexName, $options);
    }

    public function createIndex(string $indexName, array $options = []): void
    {
        $this->client->indices()->create([
            'index' => $indexName,
            'body' => array_merge_recursive(
                [
                    'mappings' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                    ],
                ], $options['elasticsearch'] ?? []
            ),
        ]);
    }

    public function search(string $indexName, SearchQueryDomainObject $query): SearchResultsDomainObject
    {
        $results = $this->client->search([
            'index' => $indexName,
            'size' => $query->limit,
            'from' => ($query->page - 1) * $query->limit,
            'sort' => $query->sort->field.':'.$query->sort->direction,
            'body' => [
                ...($query->query ? ['query' => [
                    'multi_match' => [
                        'query' => $query->query,
                        'fields' => $query->fields,
                        // 'type' => 'phrase',
                        // 'operator' => 'and'
                    ],
                ],
                ] : []),
            ],
        ]);

        $itemIds = [];
        foreach ($results['hits']['hits'] as $hit) {
            $itemIds[] = $hit['_source']['objectID'];
        }

        return new SearchResultsDomainObject($itemIds, $results['hits']['total']['value']);
    }

    public function saveObjects(string $indexName, $objects): void
    {
        $params = ['body' => []];

        foreach ($objects as $key => $object) {
            $params['body'][] = [
                'index' => [
                    '_index' => $indexName,
                    '_id' => $object['objectID'],
                ],
            ];

            $params['body'][] = $object;

            // Every 1000 documents stop and send the bulk request
            if (0 == $key % 1000) {
                $responses = $this->client->bulk($params);

                // erase the old bulk request
                $params = ['body' => []];

                // unset the bulk response when you are done to save memory
                unset($responses);
            }
        }

        if (!empty($params['body'])) {
            $this->client->bulk($params);
        }
    }

    public function deleteObjects(string $indexName, $objectIds): void
    {
        foreach ($objectIds as $objectId) {
            $this->client->delete(['index' => $indexName, '_id' => $objectId]);
        }
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
