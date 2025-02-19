<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApiTokenRepository::class)]
#[ORM\Table(name: '`api_token`')]
class ApiToken
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    private string $token;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
