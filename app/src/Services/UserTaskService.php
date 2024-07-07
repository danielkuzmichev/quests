<?php

namespace App\Services;

use App\Repository\UserTaskRepository;
use App\Enum\UserTaskStatus;
use App\Services\UserService;
use App\Services\TaskService;
use App\Entity\UserTask;
use App\Exception\NotFoundUserTaskException;
use App\Exception\TaskAlreadyCompletedException;
use Doctrine\ORM\EntityManagerInterface;

class UserTaskService
{
    private UserTaskRepository $userTaskRepository;

    private UserService $userService;

    private TaskService $taskService;

    private EntityManagerInterface $entityManager;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserTaskRepository $userTaskRepository,
        UserService $userService,
        TaskService $taskService,
        EntityManagerInterface $entityManager
    ) {
        $this->userTaskRepository = $userTaskRepository;
        $this->userService = $userService;
        $this->taskService = $taskService;
        $this->entityManager = $entityManager;
    }

    public function createUserTask(int $userId, int $taskId): void
    {
        $user = $this->userService->selectUser($userId);
        $task = $this->taskService->selectTask($taskId);
        $userTask = new UserTask($user, $task);
        $this->userTaskRepository->save($userTask, true);
    }

    public function completeUserTask(int $userId, int $taskId): void
    {
        $userTask = $this->userTaskRepository->findOneBy([
            'user' => $userId,
            'task' => $taskId
        ]);
        if(!$userTask)  {
            throw new NotFoundUserTaskException(sprintf("Task for user with id='%s' not found", $userId));
        }
        if ($userTask->getStatus() === UserTaskStatus::COMPLETED) {
            throw new TaskAlreadyCompletedException(
                data: [ 'date_of_completion' => $userTask->getDateOfCompletion()],
            );
        }
        try {
            $em = $this->entityManager;
            $em->beginTransaction();
            $userTask->setStatus(UserTaskStatus::COMPLETED);
            $userTask->setDateOfCompletion(new \DateTime());
            $this->userTaskRepository->save($userTask, true);
            $user = $userTask
                ->getUser()
                ->addPoints(
                    $userTask->getTask()->getCost()
                );
            $this->userService->updateUser($user, true);
            $em->commit();
        } catch (\Exception $ex) {
            $em->rollback();
            throw $ex;
        }
    }

    public function getHistory(int $userId): array
    {
        $userTasks = $this->userTaskRepository->getCompletedTasksByUserId($userId);
        $tasks = [];
        if (!empty($userTasks)) {
            $tasks = array_map(
                function ($userTask) {
                    $item = $userTask->getTask()->serialize();
                    $item['date_of_completion'] = $userTask->getDateOfCompletion();
                    return $item;
                },
                $userTasks
            );
        }
        return [
            'completed_tasks' => $tasks,
            'user_balance' => $this->userService
                ->selectUser($userId)
                ->getBalance()
        ];
    }

}
