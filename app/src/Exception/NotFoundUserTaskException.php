<?php

namespace App\Exception;

use App\Exception\AbstractApiException;

class NotFoundUserTaskException extends AbstractApiException
{
    public function __construct(
        string $message = 'User task is not found',
        int $code = 404,
        mixed $data = []
    )
    {
        parent::__construct($message, $code, $data);
    }
}