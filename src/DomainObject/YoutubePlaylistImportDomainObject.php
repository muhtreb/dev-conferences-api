<?php

namespace App\DomainObject;

use App\Entity\ConferenceEdition;

class YoutubePlaylistImportDomainObject
{
    public ConferenceEdition $conferenceEdition;
    public string $playlistId;
}