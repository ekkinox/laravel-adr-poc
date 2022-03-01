<?php

namespace App\Http\Controllers;

use App\Domain\DomainService;
use App\Responder\ResponderInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Example of ADR action: invokable class responsible for one http method and route
class SuccessAction
{
    // The domain service and the responder are injected as dependencies
    public function __construct(
        private DomainService $domainService,
        private ResponderInterface $responder
    ) {}

    public function __invoke(Request $request): Response
    {
        // The action calls the injected domain service,
        // and we delegate the response handling to the injected responder (using domain payload),
        // making this action agnostic of domain and response concerns.
        return $this->responder->respond(
            $this->domainService->getDomainData($request->query('input'))
        );
    }
}
