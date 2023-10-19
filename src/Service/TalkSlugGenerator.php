<?php

namespace App\Service;

use App\Entity\Talk;
use App\Repository\TalkRepository;
use Cocur\Slugify\Slugify;

class TalkSlugGenerator
{
    private static int $count = 1;

    public function __construct(
        private readonly TalkRepository $talkRepository
    ) {
    }

    public function generateSlug(string $name, ?Talk $talk = null): string
    {
        if (self::$count > 1) {
            $name .= ' ' . (self::$count - 1);
        }

        $slug = (new Slugify())->slugify($name);
        if ($this->talkRepository->checkSlugExists($slug, $talk?->getId())) {
            self::$count++;
            return $this->generateSlug($name, $talk);
        }

        self::$count = 1;
        return $slug;
    }
}
