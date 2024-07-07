<?php

namespace App\Services;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Exception\NotFoundTaskException;

class TaskService
{
    public function __construct(private TaskRepository $taskRepository)
    {
    }

    public function createTask(Task $task): void
    {
        $this->taskRepository->save($task, true);
    }

    public function deleteTask(int $id): void
    {
        try {
            $task = $this->selectTask($id);
        } catch (NotFoundTaskException) {
            throw new NotFoundTaskException('You cannot delete a non-existent task');
        }
        $this->taskRepository->remove($task, true);
    }

    public function selectTask(int $id): Task
    {
        $task = $this->taskRepository->select($id);
        if (!$task) {
            throw new NotFoundTaskException(sprintf('Task with id "%s" not found', $id));
        }
        return $task;
    }

    public function getTasksByIds(array $ids): array
    {
        return $this->taskRepository->getTasksByIds($ids);
    }

}
