<?php

namespace App\Entity;

use App\Repository\TalkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TalkRepository::class)]
class Talk implements SluggableEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id;

    #[ORM\ManyToOne(targetEntity: ConferenceEdition::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ConferenceEdition $conferenceEdition;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $slug;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'date', nullable: false)]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $youtubeId;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $position;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $apiData = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $thumbnailImageUrl = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $posterImageUrl = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?string $legacyId = null;

    #[ORM\OneToMany(mappedBy: 'talk', targetEntity: SpeakerTalk::class)]
    private Collection $speakers;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'yes')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->speakers = new ArrayCollection();
    }

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getYoutubeId(): string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(string $youtubeId): self
    {
        $this->youtubeId = $youtubeId;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getApiData(): ?array
    {
        return $this->apiData;
    }

    public function setApiData(?array $apiData): self
    {
        $this->apiData = $apiData;

        return $this;
    }

    public function getThumbnailImageUrl(): ?string
    {
        return $this->thumbnailImageUrl;
    }

    public function setThumbnailImageUrl(?string $thumbnailImageUrl): self
    {
        $this->thumbnailImageUrl = $thumbnailImageUrl;

        return $this;
    }

    public function getPosterImageUrl(): ?string
    {
        return $this->posterImageUrl;
    }

    public function setPosterImageUrl(?string $posterImageUrl): self
    {
        $this->posterImageUrl = $posterImageUrl;

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

    public function getSpeakers(): Collection
    {
        return $this->speakers;
    }

    public function addSpeaker(Speaker $speaker): self
    {
        if (!$this->speakers->contains($speaker)) {
            $this->speakers->add($speaker);
            $speaker->addTalk($this);
        }

        return $this;
    }

    public function removeSpeaker(Speaker $speaker): self
    {
        if ($this->speakers->removeElement($speaker)) {
            $speaker->removeTalk($this);
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

    public function getSluggableName(): string
    {
        return $this->name;
    }
}
