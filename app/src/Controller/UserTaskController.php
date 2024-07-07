<?php

namespace App\Controller;

use App\DTO\UserTaskDTO;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\UserTaskService;
use OpenApi\Attributes as OA;

class UserTaskController extends AbstractController
{
    public function __construct(private UserTaskService $userTaskService)
    {
    }

    #[OA\Post(
        summary: 'Add user task',
        description: 'Creates a new user task.',
        tags: ['User Task'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: UserTaskDTO::class)
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(type: 'string', example: 'OK')
            ),
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(
                    oneOf: [
                        new OA\Schema(type: 'string', example: 'User not found'),
                        new OA\Schema(type: 'string', example: 'Task not found')
                    ]
                )
            ),
        ]
    )]
    #[Route('/user/task/add', name: 'app_user_task_add', methods: 'POST')]
    public function add(#[MapRequestPayload] UserTaskDTO $userTaskDTO): JsonResponse
    {
        $this->userTaskService->createUserTask(
            $userTaskDTO->getUserId(),
            $userTaskDTO->getTaskId()
        );

        return new JsonResponse('OK');
    }

    #[OA\Post(
        summary: 'Complete user task',
        description: 'Completes a user task by marking it as completed.',
        tags: ['User Task'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: UserTaskDTO::class)
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
    #[Route('/user/task/complete', name: 'app_user_task_complete', methods: 'POST')]
    public function complete(#[MapRequestPayload] UserTaskDTO $userTaskDTO): JsonResponse
    {
        $this->userTaskService->completeUserTask(
            $userTaskDTO->getUserId(),
            $userTaskDTO->getTaskId(),
        );
        
        return new JsonResponse('OK');
    }

    #[OA\Get(
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description:"User id",
                schema: new OA\Schema(type: "integer"),
            ),
        ],
        summary: "Get completed task IDs and user balance",
        tags: ["User Task"],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of completed task IDs and user balance",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "completed_tasks",
                            type: "array",
                            items: new OA\Items(type: "integer"),
                            example: [1, 2, 3, 4, 5]
                        ),
                        new OA\Property(
                            property: "user_balance",
                            type: "number",
                            format: "integer",
                            example: 100,
                        ),
                    ]
                )
            )
        ]
    )]
    #[Route('/user/{id}/task/history', name: 'app_user_task_history', methods: 'GET')]
    public function getHistory(int $id): JsonResponse
    {
        return new JsonResponse($this->userTaskService->getHistory($id));
    }
}
