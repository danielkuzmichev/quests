<?php

namespace App\Exception;

use App\Exception\AbstractApiException;

class TaskAlreadyCompletedException extends AbstractApiException
{
    public function __construct(
        string $message = 'The task has already been completed',
        int $code = 422,
        mixed $data = []
    )
    {
        parent::__construct($message, $code, $data);
    }
}