<?php

namespace App\DomainObject\Search;

class SearchQueryDomainObject
{
    public SearchSortDomainObject $sort;

    public function __construct(
        public string $query,
        public array $fields = [],
        public ?int $limit = null,
        public int $page = 1,
        ?string $sortField = null,
        ?string $sortDirection = null,
    ) {
        $this->sort = new SearchSortDomainObject($sortField, $sortDirection);
    }
}
