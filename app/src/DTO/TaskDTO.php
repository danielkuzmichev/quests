<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDTO
{
    #[Assert\NotBlank(message:"Title cannot be blank.")]
    private string $title;

    #[Assert\NotBlank(message:"Description cannot be blank.")]
    private ?string $description;

    #[Assert\GreaterThanOrEqual(value: 0, message: "Cost must be greater than or equal to zero.")]
    private int $cost;

    public function __construct(string $title, ?string $description = null, int $cost)
    {
        $this->title = $title;
        $this->description = $description;
        $this->cost = $cost;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'cost' => $this->cost,
        ];
    }
}
