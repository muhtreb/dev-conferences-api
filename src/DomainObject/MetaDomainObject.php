<?php

namespace App\DomainObject;

class MetaDomainObject
{
    public int $page;
    public int $nbPages;
    public ?int $nextPage = null;
    public ?int $prevPage = null;
    public int $nbHits;

    public function __construct(int $page, int $count, int $limit)
    {
        $this->page = $page;
        $this->nbPages = (int) ceil($count / $limit);
        $this->nextPage = $page < $this->nbPages ? $page + 1 : null;
        $this->prevPage = $page > 1 ? $page - 1 : null;
        $this->nbHits = $count;
    }
}
