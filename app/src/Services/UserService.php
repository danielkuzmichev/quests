<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;

class UserService
{
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function createUser(Request $request): void
    {
        $user = new User();
        $user->setName(
            $request->request->get('name')
        );
        $this->userRepository->save($user, true);
    }

    public function updateUser(User $user): void
    {
        $this->userRepository->save($user, true);
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->remove($user, true);
    }

    public function selectUser(int $id): User
    {
        $user = $this->userRepository->select($id);
        if (!$user) {
            throw new EntityNotFoundException(sprintf('User with id "%s" not found', $id));
        }
        return $user;
    }

}
