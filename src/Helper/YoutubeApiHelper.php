<?php

namespace App\Helper;

class YoutubeApiHelper
{
    /**
     * @param array $data
     * @return bool
     */
    public function isPublishedVideo(array $data): bool
    {
        return isset($data['contentDetails']['videoPublishedAt']);
    }

    public function getThumbnailImage(array $data): ?string
    {
        if (null === $pictures = ($data['snippet']['thumbnails'] ?? null)) {
            return null;
        }

        return $pictures['medium']['url'] ?? $pictures['default']['url'] ?? null;
    }

    public function getMaxResImage(array $data): ?string
    {
        if (null === $pictures = ($data['snippet']['thumbnails'] ?? null)) {
            return null;
        }

        return $pictures['maxres']['url']
            ?? $pictures['standard']['url']
            ?? $pictures['high']['url']
            ?? $this->getThumbnailImage($data);
    }

    public function getVideoUrl(array $data): ?string
    {
        if (null === $id = ($data['id'] ?? null)) {
            return null;
        }

        return "https://www.youtube.com/watch?v={$id}";
    }

    public function getVideoDurationInSeconds(array $data): ?int
    {
        if (null === $duration = ($data['contentDetails']['duration'] ?? null)) {
            return null;
        }

        try {
            $dateInterval = new \DateInterval($duration);
            return ($dateInterval->days * 86400) + ($dateInterval->h * 3600) + ($dateInterval->i * 60) + $dateInterval->s;
        } catch (\Exception $e) {
            return null;
        }
    }
}