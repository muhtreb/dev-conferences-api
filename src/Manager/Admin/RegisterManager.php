<?php

namespace App\Manager\Admin;

use App\DomainObject\RegisterDomainObject;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class RegisterManager
{
    public function __construct(
        public UserRepository $userRepository,
        public UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function createUserFromDTO(RegisterDomainObject $dto): User
    {
        $user = (new User())
            ->setEmail($dto->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));

        $this->userRepository->save($user);

        return $user;
    }
}
