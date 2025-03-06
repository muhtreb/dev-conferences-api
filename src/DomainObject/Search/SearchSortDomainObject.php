<?php

namespace App\DomainObject\Search;

class SearchSortDomainObject
{
    public function __construct(
        public ?string $field = null,
        public ?string $direction = null,
    ) {
    }
}
