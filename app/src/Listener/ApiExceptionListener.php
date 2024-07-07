<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Exception\AbstractApiException;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AbstractApiException) {
            $response = new JsonResponse($exception->serialize());
            $response->setStatusCode($exception->getCode());
            $event->setResponse($response);
        }
    }
}