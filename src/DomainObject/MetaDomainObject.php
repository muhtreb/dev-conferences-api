<?php

namespace App\DomainObject;

class MetaDomainObject
{
    public int $page;
    public int $nbPages;
    public ?int $nextPage;
    public ?int $prevPage;
    public int $nbHits;

    public static function create(int $page, int $count): self
    {
        $meta = new self();
        $meta->page = $page;
        $meta->nbPages = (int) ceil($count / 10);
        $meta->nextPage = $page < $meta->nbPages ? $page + 1 : null;
        $meta->prevPage = $page > 1 ? $page - 1 : null;
        $meta->nbHits = $count;

        return $meta;
    }
}
