<?php

namespace App\Service;

interface SearchClient
{
    public function search(string $indexName, ?string $query = null, array $params = []): array;

    public function saveObjects(string $indexName, $objects): void;

    public function deleteObjects(string $indexName, $objectIds): void;

    public function replaceAllObjects(string $indexName, $objects): void;

    public function updateSortableAttributes(string $indexName, $sortableAttributes): void;
}
