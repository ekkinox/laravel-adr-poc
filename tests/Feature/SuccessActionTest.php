<?php

namespace Tests\Feature;

use App\Domain\DomainService;
use App\Responders\ContentNegotiationResponder;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Serializer\SerializerInterface;
use Tests\TestCase;

class SuccessActionTest extends TestCase
{
    public function testSuccessWithJsonResponder()
    {
        $response = $this->get('/success');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $this->assertEquals(
            app(DomainService::class)->getDomainData(),
            $response->json()
        );
    }

    public function testSuccessWithJsonContentNegotiationResponder()
    {
        Config::set('adr.responder', ContentNegotiationResponder::class);

        $response = $this->get('/success', ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');

        $this->assertEquals(
            app(DomainService::class)->getDomainData(),
            $response->json()
        );
    }

    public function testSuccessWithXmlContentNegotiationResponder()
    {
        Config::set('adr.responder', ContentNegotiationResponder::class);

        $response = $this->get('/success', ['Accept' => 'application/xml']);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');

        $this->assertEquals(
            app(DomainService::class)->getDomainData(),
            app(SerializerInterface::class)->decode($response->getContent(), 'xml'),
        );
    }

    public function testSuccessWithCsvContentNegotiationResponder()
    {
        Config::set('adr.responder', ContentNegotiationResponder::class);

        $response = $this->get('/success', ['Accept' => 'text/csv']);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->assertEquals(
            app(DomainService::class)->getDomainData(),
            app(SerializerInterface::class)->decode($response->getContent(), 'csv')[0] ?? [],
        );
    }
}
