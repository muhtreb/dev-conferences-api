<?php

namespace App\Controller;

use App\DomainObject\MetaDomainObject;

trait SearchTrait
{
    public function getMeta(array $data): MetaDomainObject
    {
        $page = $data['page'];

        $meta = new MetaDomainObject();
        $meta->page = $page;
        $meta->nbPages = $data['totalPages'];
        $meta->nextPage = $page < $data['totalPages'] ? $page + 1 : null;
        $meta->prevPage = ($page > 1) ? $page - 1 : null;
        $meta->nbHits = $data['totalHits'];

        return $meta;
    }
}
