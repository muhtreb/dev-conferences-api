<?php

namespace App\Controller;

trait SearchTrait
{
    public function getMeta(array $data): array
    {
        $page = $data['page'];

        return [
            'page' => $page,
            'nbPages' => $data['totalPages'],
            'nextPage' => $page < $data['totalPages'] ? $page + 1 : null,
            'prevPage' => ($page > 1) ? $page - 1 : null,
            'nbHits' => $data['totalHits'],
        ];
    }
}
