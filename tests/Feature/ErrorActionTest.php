<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ErrorActionTest extends TestCase
{
    public function testErrorWithJsonResponder()
    {
        $response = $this->get('/error');

        $response->assertStatus(418);
        $response->assertHeader('Content-Type', 'application/json');

        $body = $response->json();

        $this->assertEquals('Yo teapot !', $body['error']);
        $this->assertArrayNotHasKey('trace', $body);
    }

    public function testErrorWithJsonResponderAndTraces()
    {
        Config::set('app.debug', true);

        $response = $this->get('/error');

        $response->assertStatus(418);
        $response->assertHeader('Content-Type', 'application/json');

        $body = $response->json();

        $this->assertEquals('Yo teapot !', $body['error']);
        $this->assertArrayHasKey('trace', $body);
    }
}
