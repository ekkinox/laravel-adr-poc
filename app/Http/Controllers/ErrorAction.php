<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Example of ADR error action: invokable class responsible for one http method and route
class ErrorAction
{
    public function __invoke(Request $request): Response
    {
        // Throwing an exception and letting it "bubble up" will trigger the responder on error handler rendering
        abort(418, 'Yo teapot !');
    }
}
