<?php

use App\Responders\ContentNegotiationResponder;
use App\Responders\JsonResponder;

return [
    /*
    |--------------------------------------------------------------------------
    | Responder type
    |--------------------------------------------------------------------------
    |
    | Here you can define which responder you want to use on app level
    |
    */
    //'responder' => JsonResponder::class,
    'responder' => ContentNegotiationResponder::class,

    /*
    |--------------------------------------------------------------------------
    | Content negotiation priorities
    |--------------------------------------------------------------------------
    |
    | Here you can define priorities for media type negotiation
    | (see https://github.com/willdurand/Negotiation#media-type-negotiation)
    |
    */
    'content-negotiation-priorities' => [
        'text/html; charset=UTF-8',
        'application/json',
        'application/xml;q=0.5',
        'text/csv;q=0.1',
    ]
];
