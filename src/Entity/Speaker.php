<?php

namespace App\Entity;

use App\Repository\SpeakerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SpeakerRepository::class)]
class Speaker
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $lastName;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $twitter = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $github = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $speakerDeck = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nationalityIso3 = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?string $legacyId = null;

    #[ORM\OneToMany(mappedBy: 'speaker', targetEntity: SpeakerTalk::class)]
    private Collection $talks;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'speakers')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->talks = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Speaker
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Speaker
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Speaker
    {
        $this->description = $description;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): Speaker
    {
        $this->website = $website;
        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): Speaker
    {
        $this->twitter = $twitter;
        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): Speaker
    {
        $this->github = $github;
        return $this;
    }

    public function getSpeakerDeck(): ?string
    {
        return $this->speakerDeck;
    }

    public function setSpeakerDeck(?string $speakerDeck): Speaker
    {
        $this->speakerDeck = $speakerDeck;
        return $this;
    }

    public function getNationalityIso3(): ?string
    {
        return $this->nationalityIso3;
    }

    public function setNationalityIso3(?string $nationalityIso3): Speaker
    {
        $this->nationalityIso3 = $nationalityIso3;
        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): Speaker
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }

    public function getLegacyId(): ?string
    {
        return $this->legacyId;
    }

    public function setLegacyId(?string $legacyId): self
    {
        $this->legacyId = $legacyId;
        return $this;
    }

    public function getTalks(): Collection
    {
        return $this->talks;
    }

    public function addTalk(Talk $talk): self
    {
        if (!$this->talks->contains($talk)) {
            $this->talks->add($talk);
            $talk->addSpeaker($this);
        }

        return $this;
    }

    public function removeTalk(Talk $talk): self
    {
        if ($this->talks->removeElement($talk)) {
            $talk->removeSpeaker($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
