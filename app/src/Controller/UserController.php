<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;

class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/user/{id}', name: 'app_user', methods: 'GET')]
    public function selectUser(int $id): JsonResponse
    {

        try {
            $user = $this->userService->selectUser($id);
            return new JsonResponse($user->json_serialize(), 200);
        } catch (EntityNotFoundException $ex) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/user', methods: 'POST')]
    public function createUser(Request $request): JsonResponse
    {
        $this->userService->createUser($request);

        return new JsonResponse('OK');
    }

    #[Route('/user/{id}', methods: 'DELETE')]
    public function deleteUser(int $id): JsonResponse
    {
        try {
            $user = $this->userService->selectUser($id);
            $this->userService->deleteUser($user);
        } catch (EntityNotFoundException $ex) {
            return new JsonResponse(['error' => 'You cannot delete a non-existing user'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse('OK');
    }
}
