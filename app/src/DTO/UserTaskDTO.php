<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserTaskDTO
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $userId;

    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $taskId;

    public function __construct(int $userId, int $taskId)
    {
        $this->userId = $userId;
        $this->taskId = $taskId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }
}