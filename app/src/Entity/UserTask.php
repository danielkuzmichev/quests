<?php

namespace App\Entity;

use App\Enum\UserTaskStatus;
use App\Repository\UserTaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTaskRepository::class)]
#[ORM\Table(name: "usertasks")]
class UserTask
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private $user;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(name: "task_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private $task;

    #[ORM\Column(type: "string", length: 20)]
    private $status;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $dateOfCompletion;

    public function __construct(
        User $user,
        Task $task,
        string $status = UserTaskStatus::NOT_COMPLETED,
        ?\DateTimeInterface $dateOfCompletion = null)
    {
        $this->user = $user;
        $this->task = $task;
        $this->status = $status;
        $this->dateOfCompletion = $dateOfCompletion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateOfCompletion(): ?\DateTimeInterface
    {
        return $this->dateOfCompletion;
    }

    public function setDateOfCompletion(?\DateTimeInterface $dateOfCompletion): self
    {
        $this->dateOfCompletion = $dateOfCompletion;

        return $this;
    }

    public function serialize(): array
    {
        return [
            'user_id' => $this->user->serialize(),
            'task_id' => $this->task->serialize(),
            'date_of_completion' => $this->dateOfCompletion,
        ];
    }
}
