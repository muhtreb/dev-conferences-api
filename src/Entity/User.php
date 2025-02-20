<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserFavoriteConference::class)]
    private PersistentCollection $userFavoriteConferences;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserFavoriteConferenceEdition::class)]
    private PersistentCollection $userFavoriteConferenceEditions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserFavoriteSpeaker::class)]
    private PersistentCollection $userFavoriteSpeakers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserFavoriteTalk::class)]
    private PersistentCollection $userFavoriteTalks;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getUserFavoriteConferences(): PersistentCollection
    {
        return $this->userFavoriteConferences;
    }

    public function getUserFavoriteConferenceEditions(): PersistentCollection
    {
        return $this->userFavoriteConferenceEditions;
    }

    public function getUserFavoriteSpeakers(): PersistentCollection
    {
        return $this->userFavoriteSpeakers;
    }

    public function getUserFavoriteTalks(): PersistentCollection
    {
        return $this->userFavoriteTalks;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }
}
