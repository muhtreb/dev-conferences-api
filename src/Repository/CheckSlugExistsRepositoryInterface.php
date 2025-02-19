<?php

namespace App\Repository;

use Symfony\Component\Uid\Uuid;

interface CheckSlugExistsRepositoryInterface
{
    public function checkSlugExists(string $slug, ?Uuid $uuid): bool;
}