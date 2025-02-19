<?php

namespace App\Api\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class YoutubeApiClient implements YoutubeApiClientInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, private readonly string $googleApiKey)
    {
        $this->client = $client->withOptions([
            'base_uri' => 'https://www.googleapis.com',
        ]);
    }

    public function getPlaylistById(string $id): array
    {
        $response = $this->client->request(
            'GET',
            '/youtube/v3/playlists',
            [
                'query' => [
                    'key' => $this->googleApiKey,
                    'part' => 'snippet,contentDetails',
                    'id' => $id,
                ]
            ]
        );

        if (200 !== $statusCode = $response->getStatusCode()) {
            throw new \Exception('Error status code ' . $statusCode);
        }

        $playlists = $response->toArray();

        if (count($playlists['items']) === 0) {
            throw new \Exception('no items');
        }

        return $playlists['items'];
    }

    public function getPlaylistItemsById(string $id): array
    {
        $query = [
            'key' => $this->googleApiKey,
            'part' => 'snippet,contentDetails,status',
            'maxResults' => 50,
            'playlistId' => $id,
        ];

        $playlistItemsList = [];

        do {
            $response = $this->client->request(
                'GET',
                '/youtube/v3/playlistItems',
                [
                    'query' => $query
                ]
            );

            if (200 !== $statusCode = $response->getStatusCode()) {
                throw new \Exception('Unable to get playlist items. Status code : ' . $statusCode);
            }

            $playlistItems = $response->toArray();
            foreach ($playlistItems['items'] as $playlistItem) {
                $playlistItemsList[] = $playlistItem;
            }

            $nextPageToken = $playlistItems['nextPageToken'] ?? false;
            if (false !== $nextPageToken) {
                $query['pageToken'] = $nextPageToken;
            } else {
                unset($query['pageToken']);
            }
        } while (false !== $nextPageToken);

        return $playlistItemsList;
    }

    public function getVideoById(string $id): array
    {
        $response = $this->client->request(
            'GET',
            '/youtube/v3/videos',
            [
                'query' => [
                    'key' => $this->googleApiKey,
                    'part' => 'snippet,contentDetails,status',
                    'id' => $id,
                ]
            ]
        );

        if (200 !== $statusCode = $response->getStatusCode()) {
            throw new \Exception('Error status code ' . $statusCode);
        }

        $data = $response->toArray();

        return $data;
    }
}
