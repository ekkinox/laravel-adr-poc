<?php

namespace App\Providers;

use App\Responder\ContentNegotiation\RequestContentTypeNegotiator;
use App\Responder\ContentNegotiation\RequestContentTypeNegotiatorInterface;
use App\Responder\ContentNegotiationResponder;
use App\Responder\JsonSerializerResponder;
use App\Responder\ResponderInterface;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Negotiation\Negotiator;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

// Example of provider that could be part of an ADR composer package we could create (to install when we want ADR)
class ADRServiceProvider extends ServiceProvider
{
    public function register()
    {
        // We add SF serializer because it's convenient for this PoC needs.
        $this->app->singleton(SerializerInterface::class, function ($app) {
            $encoders = [new JsonEncoder(), new XmlEncoder(), new CsvEncoder()];
            $normalizers = [new ObjectNormalizer()];

            return new Serializer($normalizers, $encoders);
        });

        // We add a request content-type negotiator because it's also convenient for this PoC needs.
        $this->app->singleton(RequestContentTypeNegotiatorInterface::class, function ($app) {
            return new RequestContentTypeNegotiator(
                new Negotiator(),
                config('adr.content-negotiation-priorities')
            );
        });

        // We bind the responder defined in config to the ResponderInterface (default = JsonSerializerResponder)
        $this->app->singleton(ResponderInterface::class, function ($app) {
            return match (config('adr.responder')) {
                ContentNegotiationResponder::class => new ContentNegotiationResponder(
                    $app->get(SerializerInterface::class),
                    $app->get(RequestContentTypeNegotiatorInterface::class),
                    $app->get(Request::class),
                    boolval(env('APP_DEBUG', false))
                ),
                default => new JsonSerializerResponder(
                    boolval(env('APP_DEBUG', false))
                ),
            };
        });
    }
}
