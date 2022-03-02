<?php

namespace Tests\Unit\Domain;

use App\Domain\DomainService;
use PHPUnit\Framework\TestCase;

class DomainServiceTest extends TestCase
{
    public function testDomainDataWithoutInput()
    {
        $this->assertEquals(
            [
                'foo' => 'bar',
                'baz' => [
                    'alice',
                    'bob',
                ],
                'input' => 'n/a'
            ],
            app(DomainService::class)->getDomainData()
        );
    }

    public function testDomainDataWithInput()
    {
        $this->assertEquals(
            [
                'foo' => 'bar',
                'baz' => [
                    'alice',
                    'bob',
                ],
                'input' => 'value'
            ],
            app(DomainService::class)->getDomainData('value')
        );
    }
}
