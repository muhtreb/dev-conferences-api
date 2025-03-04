<?php

namespace App\Service\Search\Client;

use App\DomainObject\Search\SearchQueryDomainObject;
use App\DomainObject\Search\SearchResultsDomainObject;
use App\Enum\SearchClientEnum;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('search.client')]
interface SearchClientInterface
{
    public function search(string $indexName, SearchQueryDomainObject $query): SearchResultsDomainObject;

    public function saveObjects(string $indexName, $objects): void;

    public function createIndex(string $indexName, array $options = []): void;

    public function resetIndex(string $indexName, array $options = []): void;

    public function deleteObjects(string $indexName, $objectIds): void;

    public function replaceAllObjects(string $indexName, $objects): void;

    public function updateFilterableAttributes(string $indexName, $filterableAttributes): void;

    public function updateSortableAttributes(string $indexName, $sortableAttributes): void;

    public function updateRankingRules(string $indexName, $rankingRules): void;

    public function supports(SearchClientEnum $searchClientEnum): bool;
}
