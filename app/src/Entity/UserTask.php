<?php

namespace App\Entity;

use App\Repository\UserTaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTaskRepository::class)]
class UserTask
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'userTasks')]
    private Collection $userId;

    #[ORM\ManyToMany(targetEntity: Task::class, inversedBy: 'userTasks')]
    private Collection $taskId;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_of_completion = null;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
        $this->taskId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->userId;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->userId->contains($userId)) {
            $this->userId->add($userId);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        $this->userId->removeElement($userId);

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTaskId(): Collection
    {
        return $this->taskId;
    }

    public function addTaskId(Task $taskId): static
    {
        if (!$this->taskId->contains($taskId)) {
            $this->taskId->add($taskId);
        }

        return $this;
    }

    public function removeTaskId(Task $taskId): static
    {
        $this->taskId->removeElement($taskId);

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDateOfCompletion(): ?\DateTimeInterface
    {
        return $this->date_of_completion;
    }

    public function setDateOfCompletion(?\DateTimeInterface $date_of_completion): static
    {
        $this->date_of_completion = $date_of_completion;

        return $this;
    }
}
