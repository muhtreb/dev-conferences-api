<?php

namespace App\Service;

use App\Entity\ConferenceEdition;
use App\Repository\ConferenceEditionRepository;
use Cocur\Slugify\Slugify;

class ConferenceEditionSlugGenerator
{
    private static int $count = 1;

    public function __construct(
        private readonly ConferenceEditionRepository $conferenceEditionRepository
    ) {
    }

    public function generateSlug(string $name, ?ConferenceEdition $conferenceEdition = null): string
    {
        if (self::$count > 1) {
            $name .= ' ' . (self::$count - 1);
        }

        $slug = (new Slugify())->slugify($name);
        if ($this->conferenceEditionRepository->checkSlugExists($slug, $conferenceEdition?->getId())) {
            self::$count++;
            return $this->generateSlug($name, $conferenceEdition);
        }

        self::$count = 1;
        return $slug;
    }
}
