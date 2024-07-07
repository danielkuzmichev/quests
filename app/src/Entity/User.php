<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $balance = 0;

    #[ORM\OneToMany(targetEntity: UserTask::class, mappedBy: 'user')]
    private Collection $userTasks;

    public function __construct(
        ?string $name = null,
        ?int $balance = 0,
        ?Collection $userTasks = null
    ) {
        $this->name = $name;
        $this->balance = $balance;
        $this->userTasks = $userTasks ?? new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'balance' => $this->balance,
        ];
    }

    public function addPoints(int $points): self
    {
        $this->setBalance(
            $this->getBalance() + $points
        );

        return $this;
    }

}
