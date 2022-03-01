<?php

namespace App\Responder\ContentNegotiation;

use Illuminate\Http\Request;

// Interface if we want to change willdurand/negotiation dependency
interface RequestContentTypeNegotiatorInterface
{
    public function getBestContentType(Request $request): ?string;
}
