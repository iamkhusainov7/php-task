<?php

namespace App\EventListener;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use \Carbon\Carbon;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        // Getting our exception
        $exception = $event->getThrowable();

        if (
            $exception instanceof InvalidArgumentException
        ) {
            $response = new JsonResponse([
                'message' => $exception->getMessage(),
                'timespan' => Carbon::now()->toDateTimeLocalString()
            ], 400);

            return $event->setResponse($response);
        }

        $response = new JsonResponse([
            'message' => $exception->getMessage(),
            'timespan' => Carbon::now()->toDateTimeLocalString()
        ]);

        // Checking if $exception is HttpExceptionInterface 
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response); //send response
    }
}
