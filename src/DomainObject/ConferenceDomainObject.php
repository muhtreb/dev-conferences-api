<?php

namespace App\DomainObject;

use App\Entity\Conference;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class ConferenceDomainObject
{
    public ?Uuid $id = null;
    #[Assert\NotBlank(groups: ['create'])]
    public string $name;
    public ?string $description = null;
    public ?string $website = null;
    public ?string $twitter = null;
    public ?string $thumbnailImageUrl = null;

    public static function from(Conference $conference): self
    {
        $dto = new self();
        $dto->id = $conference->getId();
        $dto->name = $conference->getName();
        $dto->description = $conference->getDescription();
        $dto->website = $conference->getWebsite();
        $dto->twitter = $conference->getTwitter();
        $dto->thumbnailImageUrl = $conference->getThumbnailImageUrl();

        return $dto;
    }
}
