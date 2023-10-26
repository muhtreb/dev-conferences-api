<?php

namespace App\Entity;

use App\Repository\UserFavoriteTalkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserFavoriteTalkRepository::class)]
class UserFavoriteTalk extends UserFavorite
{
    #[ORM\ManyToOne(targetEntity: Talk::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private Talk $talk;

    public function getTalk(): Talk
    {
        return $this->talk;
    }

    public function setTalk(Talk $talk): self
    {
        $this->talk = $talk;
        return $this;
    }
}
