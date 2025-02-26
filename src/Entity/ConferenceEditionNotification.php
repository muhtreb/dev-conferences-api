<?php

namespace App\Entity;

use App\Repository\TalkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TalkRepository::class)]
class ConferenceEditionNotification
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(targetEntity: ConferenceEdition::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ConferenceEdition $conferenceEdition;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
