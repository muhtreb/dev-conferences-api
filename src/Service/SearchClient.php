<?php

namespace App\Service;

interface SearchClient
{
    public function search(string $indexName, ?string $query = null, array $params = []): array;

    public function saveObjects(string $indexName, $objects): void;

    public function reset(string $indexName): void;

    public function deleteObjects(string $indexName, $objectIds): void;

    public function replaceAllObjects(string $indexName, $objects): void;

    public function updateFilterableAttributes(string $indexName, $filterableAttributes): void;

    public function updateSortableAttributes(string $indexName, $sortableAttributes): void;

    public function updateRankingRules(string $indexName, $rankingRules): void;
}
