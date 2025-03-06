<?php

namespace App\DomainObject\Search;

class SearchResultMetaDomainObject
{
    public function __construct(
        public int $total,
    ) {
    }
}
