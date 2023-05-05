<?php

namespace App\Enum;

enum YoutubePlaylistImportStatusEnum: string
{
    case Pending = 'pending';
    case Error = 'error';
    case Success = 'success';
}