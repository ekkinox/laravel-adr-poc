<?php

namespace App\Responders;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

// Example of ADR simple JSON Responder, that covers most needs for a JSON based APIs
class JsonResponder implements ResponderInterface
{
    public function __construct(private bool $debug = false) {}

    public function respond($data, $code = null, $headers = []): Response
    {
        return new JsonResponse($data, $code ?? Response::HTTP_OK, $headers);
    }

    public function respondForException(Throwable $exception, $code = null, $headers = []): Response
    {
        $code = $code ?? Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
        }

        $payload = ['error' => $exception->getMessage()];

        if ($this->debug) {
            $payload['trace'] = $exception->getTrace();
        }

        return new JsonResponse($payload, $code, $headers);
    }
}
