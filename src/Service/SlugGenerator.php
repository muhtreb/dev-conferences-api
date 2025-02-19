<?php

namespace App\Service;

use App\Repository\CheckSlugExistsRepositoryInterface;
use Cocur\Slugify\Slugify;
use Symfony\Component\Uid\Uuid;

class SlugGenerator
{
    private static int $count = 1;

    private Slugify $slugify;

    public function __construct(private readonly CheckSlugExistsRepositoryInterface $repository)
    {
        $this->slugify = new Slugify();
    }

    public function __invoke(string $name, ?Uuid $uuid = null): string
    {
        if (self::$count > 1) {
            $name .= ' ' . (self::$count - 1);
        }

        $slug = $this->slugify->slugify($name);
        if ($this->repository->checkSlugExists($slug, $uuid)) {
            self::$count++;
            return $this($name, $uuid);
        }

        self::$count = 1;
        return $slug;
    }
}
