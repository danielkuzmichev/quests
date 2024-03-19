<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserTaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;
use App\Enum\UserTaskStatus;
use App\Services\UserService;
use App\Services\TaskService;
use App\Entity\UserTask;
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

    public function createUserTask(Request $request): void
    {
        $userId = $request->request->get('userId');
        $taskId = $request->request->get('taskId');
        $user = $this->userService->selectUser($userId);
        $task = $this->taskService->selectTask($taskId);
        $userTask = new UserTask();
        $userTask->setUser($user);
        $userTask->setTask($task);
        $userTask->setStatus(UserTaskStatus::NOT_COMPLETED);
        $this->userTaskRepository->save($userTask, true);
    }

    public function completeUserTask(int $userId, int $taskId): void
    {
        $userTask = $this->userTaskRepository->findOneBy([
            'user' => $userId,
            'task' => $taskId
        ]);

        if(!$userTask)  {
            throw new EntityNotFoundException(sprintf('Task for user with "%s" id not found', $userId));
        }
        if ($userTask->getStatus() === UserTaskStatus::COMPLETED) {
            throw new EntityNotFoundException(sprintf('This task has already been completed'));
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
                    $item = $userTask->getTask()->json_serialize();
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
