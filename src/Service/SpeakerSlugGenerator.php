<?php

namespace App\Service;

use App\Entity\Speaker;
use App\Repository\SpeakerRepository;
use Cocur\Slugify\Slugify;

class SpeakerSlugGenerator
{
    private static int $count = 1;

    public function __construct(
        private readonly SpeakerRepository $speakerRepository
    ) {
    }

    public function generateSlug(string $name, ?Speaker $speaker = null): string
    {
        if (self::$count > 1) {
            $name .= ' ' . (self::$count - 1);
        }

        $slug = (new Slugify())->slugify($name);
        if ($this->speakerRepository->checkSlugExists($slug, $speaker?->getId())) {
            self::$count++;
            return $this->generateSlug($name, $speaker);
        }

        self::$count = 1;
        return $slug;
    }
}
