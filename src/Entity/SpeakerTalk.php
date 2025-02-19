<?php

namespace App\Entity;

use App\Repository\SpeakerTalkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpeakerTalkRepository::class)]
class SpeakerTalk
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Speaker::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Speaker $speaker;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Talk::class, inversedBy: 'speakers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Talk $talk;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $main = false;

    public function getSpeaker(): Speaker
    {
        return $this->speaker;
    }

    public function setSpeaker(Speaker $speaker): SpeakerTalk
    {
        $this->speaker = $speaker;

        return $this;
    }

    public function getTalk(): Talk
    {
        return $this->talk;
    }

    public function setTalk(Talk $talk): SpeakerTalk
    {
        $this->talk = $talk;

        return $this;
    }

    public function isMain(): bool
    {
        return $this->main;
    }

    public function setMain(bool $main): SpeakerTalk
    {
        $this->main = $main;

        return $this;
    }
}
