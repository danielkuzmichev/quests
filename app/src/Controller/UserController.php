<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\UserService;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\DTO\UserDTO;
use OpenApi\Attributes as OA;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(private UserService $userService)
    {
    }

    #[OA\Get(
        summary: "Get user by ID",
        description: "Fetches details of a user by their ID.",
        operationId: "selectUser",
        tags: ["User"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID of the user to fetch",
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "User found and details returned",
                content: new Model(type: User::class)
            ),
            new OA\Response(
                response: 404,
                description: "User not found",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "error",
                            type: "string",
                            example: "User is not found"
                        ),
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', name: 'app_user', methods: 'GET')]
    public function selectUser(int $id): JsonResponse
    {
        $user = $this->userService->selectUser($id);
        
        return new JsonResponse($user->serialize(), 200);
    }

    #[OA\Post(
        summary: 'Create a new user',
        description: 'Creates a new user.',
        tags: ['User'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: UserDTO::class)
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(type: 'string', example: 'OK')
            ),
            new OA\Response(
                response: 400,
                description: 'Validation Error',
                content: new OA\JsonContent(type: 'string', example: 'Invalid data')
            )
        ]
    )]
    #[Route('/create', methods: 'POST')]
    public function createUser(#[MapRequestPayload] UserDTO $userDTO): JsonResponse
    {
        $this->userService->createUser(
            new User(...$userDTO->toArray())
        );

        return new JsonResponse('OK');
    }

    #[OA\Delete(
        summary: "Delete a user by ID",
        description: "Deletes a user from the system by their ID.",
        tags: ["User"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID of the user to delete",
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "User successfully deleted",
                content: new OA\JsonContent(
                    type: "string",
                    example: "OK"
                )
            ),
            new OA\Response(
                response: 404,
                description: "User not found",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "error",
                            type: "string",
                            example: "You cannot delete a non-existent user"
                        ),
                    ]
                )
            ),
        ]
    )]
    #[Route('/{id}', methods: 'DELETE')]
    public function deleteUser(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);

        return new JsonResponse('OK');
    }
}
