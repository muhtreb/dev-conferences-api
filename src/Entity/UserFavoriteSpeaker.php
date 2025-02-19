<?php

namespace App\Entity;

use App\Repository\UserFavoriteTalkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserFavoriteTalkRepository::class)]
class UserFavoriteSpeaker extends UserFavorite
{
    #[ORM\ManyToOne(targetEntity: Speaker::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private Speaker $speaker;

    public function getSpeaker(): Speaker
    {
        return $this->speaker;
    }

    public function setSpeaker(Speaker $speaker): self
    {
        $this->speaker = $speaker;

        return $this;
    }
}
