<?php

namespace App\Entity;

use App\Repository\UserFavoriteConferenceEditionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserFavoriteConferenceEditionRepository::class)]
class UserFavoriteConferenceEdition extends UserFavorite
{
    #[ORM\ManyToOne(targetEntity: ConferenceEdition::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ConferenceEdition $conferenceEdition;

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
