<?php

namespace App\Services;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;

class TaskService
{
    private TaskRepository $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }


    public function createTask(Request $request): void
    {
        $task = new Task();
        $task->setTitle(
            $request->request->get('title')
        );
        $task->setDescription(
            $request->request->get('description')
        );
        $task->setCost(
            $request->request->get('cost')
        );
        $this->taskRepository->save($task, true);
    }

    public function deleteTask(Task $task): void
    {
        $this->taskRepository->remove($task, true);
    }

    public function selectTask(int $id): Task
    {
        $task = $this->taskRepository->select($id);
        if (!$task) {
            throw new EntityNotFoundException(sprintf('Task with id "%s" not found', $id));
        }
        return $task;
    }

    public function getTasksByIds(array $ids): array
    {
        return $this->taskRepository->getTasksByIds($ids);
    }

}
