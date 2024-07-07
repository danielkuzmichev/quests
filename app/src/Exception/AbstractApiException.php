<?php

namespace App\Exception;

abstract class AbstractApiException extends \RuntimeException
{ 
    public function __construct(
        string $message = "",
        int $code = null,
        protected readonly mixed $data = []
    )
    {
        parent::__construct($message, $code);
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function serialize(): array
    {
        $defaultOutputFields = [
            'error' => $this->getMessage(),
        ];

        return array_merge($defaultOutputFields,$this->getData());
    }
}