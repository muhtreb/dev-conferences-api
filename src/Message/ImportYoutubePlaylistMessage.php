<?php

namespace App\Message;

class ImportYoutubePlaylistMessage
{
    public function __construct(
        public int $youtubePlaylistImportId,
    )
    {
    }
}