<?php

namespace App\Manager\Admin;

use App\DomainObject\TagDomainObject;
use App\Entity\Tag;
use App\Repository\TagRepository;

readonly class TagManager
{
    public function __construct(
        private TagRepository $tagRepository
    )
    {
    }

    public function createTagFromDTO(TagDomainObject $dto): Tag
    {
        $tag = (new Tag())
            ->setName($dto->name);

        $this->tagRepository->save($tag);
        return $tag;
    }

    public function removeTag(Tag $tag): void
    {
        $this->tagRepository->remove($tag);
    }
}