<?php

namespace App\Service;

use Meilisearch\Client;

class MeilisearchClient implements SearchClient
{
    private Client $client;

    public function __construct(string $url, string $apiKey)
    {
        $this->client = new Client($url, $apiKey);
    }

    public function search(string $indexName, ?string $query = null, array $params = []): array
    {
        $index = $this->client->index($indexName);
        return $index->search($query, $params)->toArray();
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
}
