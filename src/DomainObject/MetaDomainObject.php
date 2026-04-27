<?php

namespace App\DomainObject;

class MetaDomainObject
{
    public int $nbPages;
    public ?int $nextPage = null;
    public ?int $prevPage = null;
    public int $nbHits;

    public function __construct(public int $page, int $count, int $limit)
    {
        $this->nbPages = (int) ceil($count / $limit);
        $this->nextPage = $this->page < $this->nbPages ? $this->page + 1 : null;
        $this->prevPage = $this->page > 1 ? $this->page - 1 : null;
        $this->nbHits = $count;
    }
}
