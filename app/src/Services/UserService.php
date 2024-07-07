<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Exception\NotFoundUserException;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function createUser(User $user): void
    {
        $this->userRepository->save($user, true);
    }

    public function updateUser(User $user): void
    {
        $this->userRepository->save($user, true);
    }

    public function deleteUser(int $id): void
    {
        try {
            $user = $this->selectUser($id);
        } catch (NotFoundUserException) {
            throw new NotFoundUserException('You cannot delete a non-existent user');
        }
        $this->userRepository->remove($user, true);
    }

    public function selectUser(int $id): User
    {
        $user = $this->userRepository->select($id);
        if (!$user) {
            throw new NotFoundUserException(sprintf("User with id '%s' not found", $id));
        }
        return $user;
    }

}
