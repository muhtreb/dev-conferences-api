<?php

namespace App\Entity;

use App\Enum\YoutubePlaylistImportStatusEnum;
use App\Repository\YoutubePlaylistImportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YoutubePlaylistImportRepository::class)]
class YoutubePlaylistImport
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $playlistId;

    #[ORM\Column(type: 'string', nullable: false, enumType: YoutubePlaylistImportStatusEnum::class)]
    private YoutubePlaylistImportStatusEnum $status;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $data = [];

    #[ORM\ManyToOne(targetEntity: ConferenceEdition::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ConferenceEdition $conferenceEdition;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPlaylistId(): string
    {
        return $this->playlistId;
    }

    public function setPlaylistId(string $playlistId): self
    {
        $this->playlistId = $playlistId;

        return $this;
    }

    public function getStatus(): YoutubePlaylistImportStatusEnum
    {
        return $this->status;
    }

    public function setStatus(YoutubePlaylistImportStatusEnum $status): YoutubePlaylistImport
    {
        $this->status = $status;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getConferenceEdition(): ConferenceEdition
    {
        return $this->conferenceEdition;
    }

    public function setConferenceEdition(ConferenceEdition $conferenceEdition): self
    {
        $this->conferenceEdition = $conferenceEdition;

        return $this;
    }
}
