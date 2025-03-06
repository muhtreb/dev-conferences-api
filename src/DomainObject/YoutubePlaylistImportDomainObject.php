<?php

namespace App\DomainObject;

use App\Entity\YoutubePlaylistImport;
use App\Validator\Constraints\UniqueValueInEntity;
use Symfony\Component\Validator\Constraints as Assert;

class YoutubePlaylistImportDomainObject
{
    #[Assert\NotBlank(groups: ['create'])]
    #[UniqueValueInEntity(
        entityClass: YoutubePlaylistImport::class,
        field: 'playlistId',
        groups: ['create']
    )]
    public string $playlistId;
}
