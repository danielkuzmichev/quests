<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\UserTaskService;
use Doctrine\ORM\EntityNotFoundException;

class UserTaskController extends AbstractController
{

    private UserTaskService $userTaskService;

    public function __construct(UserTaskService $userTaskService, )
    {
        $this->userTaskService = $userTaskService;
    }

    #[Route('/user/task/add', name: 'app_user_task_add', methods: 'POST')]
    public function add(Request $request): JsonResponse
    {
        $this->userTaskService->createUserTask($request);

        return new JsonResponse('OK');
    }

    #[Route('/user/task/complete', name: 'app_user_task_complete', methods: 'POST')]
    public function complete(Request $request): JsonResponse
    {
        try {
            $this->userTaskService->completeUserTask(
                $request->request->get('userId'),
                $request->request->get('taskId')
            );
        } catch (EntityNotFoundException $ex) {
            return new JsonResponse(['error' => $ex->getMessage()], Response::HTTP_NOT_FOUND);
        }
        catch (\Exception $ex) {
            return new JsonResponse(['error' => $ex->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse('OK');
    }

    #[Route('/user/{id}/task/history', name: 'app_user_task_history', methods: 'GET')]
    public function getHistory(int $id): JsonResponse
    {
        $history = $this->userTaskService->getHistory($id);

        return new JsonResponse($history);
    }
}
