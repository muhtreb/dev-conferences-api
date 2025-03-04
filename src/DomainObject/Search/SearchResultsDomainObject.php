<?php

namespace App\DomainObject\Search;

class SearchResultsDomainObject
{
    /**
     * @var SearchResultItemDomainObject[]
     */
    public array $items;
    public SearchResultMetaDomainObject $meta;

    public function __construct(
        array $itemIds,
        int $total,
    ) {
        $items = [];
        foreach ($itemIds as $itemId) {
            $items[] = new SearchResultItemDomainObject($itemId);
        }
        $this->items = $items;
        $this->meta = new SearchResultMetaDomainObject($total);
    }
}
