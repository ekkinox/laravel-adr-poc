<?php

namespace App\Responder;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

// Example of ADR Responder interface
interface ResponderInterface
{
    public function respond($data, $code = null, $headers = []): Response;

    public function respondForException(Throwable $exception, $code = null, $headers = []): Response;
}
