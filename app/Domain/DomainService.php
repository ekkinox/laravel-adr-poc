<?php

namespace App\Domain;

// Example of dummy ADR Domain service, can be anything (services, repositories, etc)
class DomainService
{
    public function getDomainData(?string $input = null): array
    {
        return [
            'foo' => 'bar',
            'baz' => [
                'alice',
                'bob',
            ],
            'input' => $input ?? 'n/a'
        ];
    }
}
