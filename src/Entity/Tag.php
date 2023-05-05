<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Conference::class, mappedBy: 'tags')]
    private Collection $conferences;

    #[ORM\ManyToMany(targetEntity: Talk::class, mappedBy: 'tags')]
    private Collection $talks;

    #[ORM\ManyToMany(targetEntity: Speaker::class, mappedBy: 'tags')]
    private Collection $speakers;

    public function __construct()
    {
        $this->conferences = new ArrayCollection();
        $this->talks = new ArrayCollection();
        $this->speakers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Conference>
     */
    public function getConferences(): Collection
    {
        return $this->conferences;
    }

    public function addConference(Conference $conference): self
    {
        if (!$this->conferences->contains($conference)) {
            $this->conferences[] = $conference;
            $conference->addTag($this);
        }

        return $this;
    }

    public function removeConference(Conference $conference): self
    {
        if ($this->conferences->removeElement($conference)) {
            $conference->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Talk>
     */
    public function getTalks(): Collection
    {
        return $this->talks;
    }

    public function addTalk(Talk $talk): self
    {
        if (!$this->talks->contains($talk)) {
            $this->talks[] = $talk;
            $talk->addTag($this);
        }

        return $this;
    }

    public function removeTalk(Talk $talk): self
    {
        if ($this->talks->removeElement($talk)) {
            $talk->removeTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Speaker>
     */
    public function getSpeakers(): Collection
    {
        return $this->speakers;
    }

    public function addSpeaker(Speaker $speaker): self
    {
        if (!$this->speakers->contains($speaker)) {
            $this->speakers->add($speaker);
            $speaker->addTag($this);
        }

        return $this;
    }

    public function removeSpeaker(Speaker $speaker): self
    {
        if ($this->speakers->removeElement($speaker)) {
            $speaker->removeTag($this);
        }

        return $this;
    }
}
