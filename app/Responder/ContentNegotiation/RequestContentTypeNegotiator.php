<?php

namespace App\Responder\ContentNegotiation;

use Illuminate\Http\Request;
use Negotiation\Negotiator;

class RequestContentTypeNegotiator implements RequestContentTypeNegotiatorInterface
{
    public function __construct(
        private Negotiator $negotiator,
        private array $priorities
    ) {}

    public function getBestContentType(Request $request): ?string
    {
        $header = $request->headers->get('Accept');

        if (empty($header)) {
            return null;
        }

        return $this->negotiator->getBest($header, $this->priorities)?->getType();
    }
}
