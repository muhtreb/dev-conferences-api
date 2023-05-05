<?php

namespace App\Repository;

use App\Entity\YoutubePlaylistImport;
use Doctrine\Persistence\ManagerRegistry;

class YoutubePlaylistImportRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YoutubePlaylistImport::class);
    }

    public function getPendingImports(): array
    {
        $connection = $this->_em->getConnection();
        $query = <<<SQL
           SELECT id, playlist_id, conference_edition_id
           FROM youtube_playlist_import
           WHERE NOT(jsonb_exists(data::jsonb, 'success'))
        SQL;
        $stmt = $connection->prepare($query);
        $result = $stmt->execute();

        return $result->fetchAll();
    }
}
