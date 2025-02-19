<?php

namespace App\Entity;

use App\Repository\UserFavoriteConferenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserFavoriteConferenceRepository::class)]
class UserFavoriteConference extends UserFavorite
{
    #[ORM\ManyToOne(targetEntity: Conference::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private Conference $conference;

    public function getConference(): Conference
    {
        return $this->conference;
    }

    public function setConference(Conference $conference): self
    {
        $this->conference = $conference;

        return $this;
    }
}
