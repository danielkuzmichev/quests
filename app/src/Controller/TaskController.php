<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\TaskService;
use App\Entity\Task;
use App\DTO\TaskDTO;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

#[Route('/task')]
class TaskController extends AbstractController
{
    public function __construct(private TaskService $taskService)
    {
    }

    #[OA\Get(
        summary: 'Get task by ID',
        description: 'Fetch a task by its ID.',
        tags: ['Task'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'The ID of the task to fetch',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'title', type: 'string', example: 'Example Task Title'),
                        new OA\Property(property: 'description', type: 'string', example: 'This is an example description of the task.'),
                        new OA\Property(property: 'cost', type: 'integer', example: 100)
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Not Found',
                content: new OA\JsonContent(type: 'string', example: 'Task not found')
            )
        ]
    )]
    #[Route('/{id}', name: 'app_task', methods: 'GET')]
    public function selectTask(int $id): JsonResponse
    {
        $task = $this->taskService->selectTask($id);
        
        return new JsonResponse($task->serialize(), 200);
    }

    #[OA\Post(
        summary: 'Creates a new task',
        description: 'Creates a new task',
        tags: ['Task'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: TaskDTO::class)
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
    public function createTask(#[MapRequestPayload] TaskDTO $taskDTO): JsonResponse
    {
        $this->taskService->createTask(
            new Task(...$taskDTO->toArray())
        );

        return new JsonResponse('OK');
    }

    #[OA\Delete(
        summary: 'Delete task by ID',
        description: 'Deletes a task by its ID.',
        tags: ['Task'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'The ID of the task to delete',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK',
                content: new OA\JsonContent(type: 'string', example: 'OK')
            ),
            new OA\Response(
                response: 404,
                description: 'Not Found',
                content: new OA\JsonContent(type: 'string', example: 'Task is not found')
            )
        ]
    )]
    #[Route('/{id}', methods: 'DELETE')]
    public function deleteTask(int $id): JsonResponse
    {
        $this->taskService->deleteTask($id);

        return new JsonResponse('OK');
    }
}
