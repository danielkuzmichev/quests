<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\TaskService;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;


class TaskController extends AbstractController
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    #[Route('/task/{id}', name: 'app_task', methods: 'GET')]
    public function selecttask(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->selecttask($id);
            return new JsonResponse($task->json_serialize(), 200);
        } catch (EntityNotFoundException $ex) {
            return new JsonResponse(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/task', methods: 'POST')]
    public function createTask(Request $request): JsonResponse
    {
        $this->taskService->createTask($request);

        return new JsonResponse('OK');
    }

    #[Route('/task/{id}', methods: 'DELETE')]
    public function deleteTask(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->selectTask($id);
            $this->taskService->deleteTask($task);
        } catch (EntityNotFoundException $ex) {
            return new JsonResponse(['error' => 'You cannot delete a non-existing task'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse('OK');
    }
}
