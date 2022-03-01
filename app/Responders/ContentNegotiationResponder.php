<?php

namespace App\Responders;

use App\Responders\ContentNegotiation\RequestContentTypeNegotiatorInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

// Example of more advanced but still simple ADR Responder with content negotiation (CSV, XML and JSON as fallback)
class ContentNegotiationResponder implements ResponderInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private RequestContentTypeNegotiatorInterface $negotiator,
        private Request $request,
        private bool $debug = false
    ) {}

    public function respond($data, $code = null, $headers = []): Response
    {
        return new Response(
            $this->serializer->serialize($data, $this->getRequestedSerializationFormat()),
            $code ?? Response::HTTP_OK,
            $headers + ['Content-Type' => $this->getRequestedContentType()]
        );
    }

    public function respondForException(Throwable $exception, $code = null, $headers = []): Response
    {
        $code = $code ?? Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
        }

        $payload = ['error' => $exception->getMessage()];

        if ($this->debug) {
            $payload['trace'] = $exception->getTraceAsString();
        }

        return new Response(
            $this->serializer->serialize($payload, $this->getRequestedSerializationFormat()),
            $code ?? Response::HTTP_OK,
            $headers + ['Content-Type' => $this->getRequestedContentType()]
        );
    }

    private function getRequestedContentType(): string
    {
        return $this->negotiator->getBestContentType($this->request) ?? 'application/json';
    }

    private function getRequestedSerializationFormat(): string
    {
        return match ($this->getRequestedContentType()) {
            'application/xml' => 'xml',
            'text/csv' => 'csv',
            default => 'json',
        };
    }
}
