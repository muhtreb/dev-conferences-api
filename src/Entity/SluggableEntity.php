<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

interface SluggableEntity
{
    public function getId(): ?Uuid;
    public function getSluggableName(): string;
}