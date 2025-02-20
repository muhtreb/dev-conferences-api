<?php

namespace App\Api\Client;

interface YoutubeApiClientInterface
{
    public function getPlaylistById(string $id): array;

    public function getPlaylistItemsById(string $id): array;

    public function getVideoById(string $id): array;
}
