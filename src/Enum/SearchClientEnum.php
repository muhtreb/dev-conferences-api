<?php

namespace App\Enum;

enum SearchClientEnum: string
{
    case Algolia = 'algolia';
    case Meilisearch = 'meilisearch';
    case Elasticsearch = 'elasticsearch';
}
